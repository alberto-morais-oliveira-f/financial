<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Am2tec\Financial\Domain\Contracts\DreRepositoryInterface;
use Am2tec\Financial\Domain\Enums\CategoryType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentDreRepository implements DreRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDreData(string $startDate, string $endDate): Collection
    {
        $entriesTable = config('financial.table_prefix', 'fin_') . 'entries';
        $transactionsTable = config('financial.table_prefix', 'fin_') . 'transactions';
        $categoriesTable = config('financial.table_prefix', 'fin_') . 'categories';

        return DB::table("{$entriesTable} as e")
            ->join("{$transactionsTable} as t", 'e.transaction_id', '=', 't.id')
            ->join("{$categoriesTable} as c", 'e.category_id', '=', 'c.id')
            ->select([
                'c.id as category_id',
                'c.name as category_name',
                'c.type as category_type',
                'c.parent_id',
                DB::raw("SUM(
                    CASE
                        WHEN c.type = 'REVENUE' THEN
                            CASE WHEN e.type = 'credit' THEN e.amount ELSE -e.amount END
                        WHEN c.type IN ('COST', 'EXPENSE', 'TAX') THEN
                            CASE WHEN e.type = 'debit' THEN e.amount ELSE -e.amount END
                        ELSE 0
                    END
                ) as total_amount")
            ])
            ->where('t.status', 'POSTED')
            ->whereIn('c.type', [
                CategoryType::REVENUE->value,
                CategoryType::COST->value,
                CategoryType::EXPENSE->value,
                CategoryType::TAX->value,
            ])
            ->whereBetween('t.updated_at', [$startDate, $endDate])
            ->groupBy('c.id', 'c.name', 'c.type', 'c.parent_id')
            ->orderBy('c.type')
            ->get();
    }
}
