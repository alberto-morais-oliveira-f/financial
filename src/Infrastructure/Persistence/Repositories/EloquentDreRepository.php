<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Am2tec\Financial\Domain\Contracts\DreRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EloquentDreRepository implements DreRepositoryInterface
{
    public function getDreData(string $startDate, string $endDate): Collection
    {
        $prefix = config('financial.table_prefix', 'fin_');
        $entriesTable = $prefix . 'entries';
        $transactionsTable = $prefix . 'transactions';
        // CORREÇÃO: A tabela de categorias tem nome fixo 'financial_categories'
        $categoriesTable = 'fin_categories';

        // Verifica se a coluna category_uuid existe na tabela de entries
        // Se não existir, provavelmente a migração não rodou ou falhou silenciosamente
        // Nesse caso, retornamos uma coleção vazia para evitar erro 500
        if (!Schema::hasColumn($entriesTable, 'category_uuid')) {
            return collect([]);
        }

        // A conversão para REAL/FLOAT pode variar um pouco entre bancos de dados,
        // mas CAST(... AS REAL) é bem padrão para SQLite e outros.
        $query = "
            SELECT
                c.uuid AS category_id,
                c.name AS category_name,
                c.type AS category_type,
                c.parent_uuid AS parent_id,
                CAST(COALESCE(SUM(
                    CASE
                        WHEN c.type = 'revenue' THEN
                            CASE WHEN e.type = 'credit' THEN e.amount ELSE -e.amount END
                        WHEN c.type IN ('cost', 'expense', 'tax') THEN
                            CASE WHEN e.type = 'debit' THEN e.amount ELSE -e.amount END
                        ELSE 0
                    END
                ), 0) AS REAL) AS total_amount
            FROM
                {$entriesTable} AS e
            JOIN
                {$transactionsTable} AS t ON e.transaction_id = t.id
            JOIN
                {$categoriesTable} AS c ON e.category_uuid = c.uuid
            WHERE
                t.status = 'POSTED'
                AND c.type IN ('revenue', 'cost', 'expense', 'tax')
                AND t.updated_at BETWEEN ? AND ?
            GROUP BY
                c.uuid, c.name, c.type, c.parent_uuid
            ORDER BY
                c.type;
        ";

        $results = DB::select($query, [$startDate, $endDate]);

        return collect($results);
    }
}
