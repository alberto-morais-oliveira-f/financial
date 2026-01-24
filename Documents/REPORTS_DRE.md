# Design de Relatório: DRE (Demonstração do Resultado do Exercício)

Este documento detalha a estratégia técnica para gerar uma DRE a partir da estrutura de dados do pacote `am2tec/financial`.

## 1. Definição Conceitual da DRE no Módulo

A DRE é um relatório de **performance econômica**, não de posição de caixa. Ela responde à pergunta: "A operação deu lucro ou prejuízo em um determinado período?".

-   **O que entra na DRE?** Apenas `Entries` (lançamentos) cujas categorias (`Category`) são do tipo "resultado". São elas:
    -   `REVENUE` (Receita)
    -   `COST` (Custo)
    -   `EXPENSE` (Despesa)
    -   `TAX` (Imposto, quando representa uma dedução do resultado)

-   **O que NÃO entra na DRE?** Lançamentos que representam apenas mutações patrimoniais (movimentação entre contas do balanço).
    -   `ASSET` (Ativo)
    -   `LIABILITY` (Passivo)
    -   `EQUITY` (Patrimônio Líquido)
    -   **Exemplo**: Uma transferência de R$ 1.000 da `Wallet` "Conta Corrente" (Ativo) para a `Wallet` "Caixa" (Ativo) não afeta o resultado. Da mesma forma, pagar uma parcela de um empréstimo debita um `LIABILITY` e credita um `ASSET`, não impactando a DRE diretamente.

-   **Reconhecimento (Regime de Competência)**: Um lançamento afeta a DRE no momento em que a transação associada a ele é confirmada (`POSTED`). A data de reconhecimento é a data em que o status da `Transaction` foi alterado para `POSTED`, que geralmente corresponde ao `updated_at` do registro da transação.

## 2. Estratégia de Cálculo do Valor da Entry

O valor de uma `Entry` para a DRE depende da sua `type` (débito/crédito) e da `type` de sua `Category`. A convenção contábil padrão é:

-   Contas de **Receita (`REVENUE`)**: Aumentam com **Crédito**.
-   Contas de **Custo (`COST`)**, **Despesa (`EXPENSE`)** e **Imposto (`TAX`)**: Aumentam com **Débito**.

Portanto, para calcular o valor líquido de uma categoria na DRE:

-   **Para `REVENUE`**: `Valor Total = SUM(créditos) - SUM(débitos)`
    -   Um débito em uma conta de receita representa uma devolução ou um estorno de venda.
-   **Para `COST`, `EXPENSE`, `TAX`**: `Valor Total = SUM(débitos) - SUM(créditos)`
    -   Um crédito em uma conta de despesa representa um estorno, um rebate ou uma recuperação de despesa.

Esta lógica é fundamental e será implementada diretamente na query SQL.

## 3. Query Base (SQL Conceitual)

A query a seguir extrai os valores agregados, aplicando a convenção de sinal.

```sql
SELECT
    c.uuid AS category_uuid,
    c.name AS category_name,
    c.type AS category_type,
    c.parent_uuid, -- Para reconstruir a hierarquia na aplicação

    -- Aplica a lógica de sinal baseada no tipo da categoria e no tipo do lançamento
    SUM(
        CASE
            -- Para Receitas, crédito é positivo, débito é negativo
            WHEN c.type = 'REVENUE' THEN
                CASE WHEN e.type = 'credit' THEN e.amount ELSE -e.amount END

            -- Para Custos, Despesas e Impostos, débito é positivo, crédito é negativo
            WHEN c.type IN ('COST', 'EXPENSE', 'TAX') THEN
                CASE WHEN e.type = 'debit' THEN e.amount ELSE -e.amount END

            ELSE 0 -- Ignora outros tipos de categoria
        END
    ) AS total_amount

FROM
    financial_entries AS e
JOIN
    financial_transactions AS t ON e.transaction_id = t.uuid
JOIN
    financial_categories AS c ON e.category_uuid = c.uuid
WHERE
    -- 1. Apenas transações confirmadas (reconhecidas)
    t.status = 'POSTED'

    -- 2. Dentro do período desejado (usando a data de atualização da transação)
    AND t.updated_at BETWEEN :start_date AND :end_date

    -- 3. Apenas categorias de resultado
    AND c.type IN ('REVENUE', 'COST', 'EXPENSE', 'TAX')
GROUP BY
    c.uuid, c.name, c.type, c.parent_uuid
ORDER BY
    c.type, c.name;
```

## 4. Query com Laravel Query Builder

A mesma lógica pode ser construída de forma limpa com o Query Builder do Laravel, usando `DB::raw` para a lógica de agregação complexa.

```php
use Illuminate\Support\Facades\DB;
use Am2tec\Financial\Domain\Enums\CategoryType;

$startDate = '2024-01-01 00:00:00';
$endDate = '2024-01-31 23:59:59';

$entriesTable = config('financial.table_prefix', 'fin_') . 'entries';
$transactionsTable = config('financial.table_prefix', 'fin_') . 'transactions';

$results = DB::table("{$entriesTable} as e")
    ->join("{$transactionsTable} as t", 'e.transaction_id', '=', 't.uuid')
    ->join('fin_categories as c', 'e.category_uuid', '=', 'c.uuid')
    ->select([
        'c.uuid as category_uuid',
        'c.name as category_name',
        'c.type as category_type',
        'c.parent_uuid',
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
    ->groupBy('c.uuid', 'c.name', 'c.type', 'c.parent_uuid')
    ->orderBy('c.type')
    ->get();
```

## 5. Exemplo de Resultado Esperado

A query retornará uma `Collection` de objetos, onde cada objeto representa o total de uma categoria no período.

```json
[
    {
        "category_uuid": "uuid-revenue-sales",
        "category_name": "Receita de Vendas",
        "category_type": "revenue",
        "parent_uuid": null,
        "total_amount": 15000000
    },
    {
        "category_uuid": "uuid-revenue-returns",
        "category_name": "Devoluções de Vendas",
        "category_type": "revenue",
        "parent_uuid": null,
        "total_amount": -500000 // Um débito em receita, corretamente negativo
    },
    {
        "category_uuid": "uuid-cost-cogs",
        "category_name": "Custo da Mercadoria Vendida",
        "category_type": "cost",
        "parent_uuid": null,
        "total_amount": 7000000
    },
    {
        "category_uuid": "uuid-expense-rent",
        "category_name": "Despesa com Aluguel",
        "category_type": "expense",
        "parent_uuid": "uuid-expense-admin",
        "total_amount": 2000000
    }
]
```

## 6. Estratégia para DRE Detalhada vs. Resumida

A query acima já fornece os dados para uma **DRE detalhada (analítica)**, no nível de cada categoria. A montagem do relatório final (resumido) deve ser feita na camada da aplicação (PHP), aproveitando as coleções do Laravel.

**Exemplo de Geração de DRE Resumida (Sintética):**

```php
// $results é a collection da query anterior

// 1. Agrupar por tipo
$groupedByType = $results->groupBy('category_type');

// 2. Somar os totais de cada grupo
$totals = $groupedByType->map(function ($group) {
    return $group->sum('total_amount');
});

// 3. Calcular os resultados
$receitaBruta = $totals->get('revenue', 0);
$custos = $totals->get('cost', 0);
$despesas = $totals->get('expense', 0);
$impostos = $totals->get('tax', 0);

$lucroBruto = $receitaBruta - $custos;
$lucroOperacional = $lucroBruto - $despesas - $impostos;

// Agora você tem os valores para montar a DRE sintética.
// A DRE analítica pode ser montada iterando sobre a collection original ($results).
```

## 7. Considerações Adicionais

-   **Ajustes e Estornos**: A lógica de `SUM(debits) - SUM(credits)` (ou o inverso) lida naturalmente com estornos. Um estorno de receita é um débito em uma categoria `REVENUE`, o que diminui o total da receita líquida, como esperado.
-   **Lançamentos com Valor Negativo**: A coluna `amount` deve ser sempre `UNSIGNED` (positiva). A direção do lançamento é definida exclusivamente pelo campo `type` (debit/credit). A lógica de negócio deve garantir isso.
-   **Performance**: Para volumes de dados massivos (milhões de `entries`), esta query pode se tornar um gargalo.
    -   **Índices**: É **mandatório** ter índices nas colunas: `transactions.status`, `transactions.updated_at`, `entries.transaction_id`, `entries.category_uuid`, e `categories.type`.
    -   **Data Warehousing**: Para relatórios em tempo real em sistemas muito grandes, a melhor abordagem é a desnormalização. Criar tabelas de resumo (ex: `financial_monthly_summary`) que são populadas periodicamente (ex: via um job noturno). A DRE seria então gerada a partir dessa tabela pré-agregada, resultando em uma performance ordens de magnitude melhor.
-   **Hierarquia**: A query retorna `parent_uuid`. A aplicação pode usar isso para montar a DRE em formato de árvore, onde as categorias filhas são aninhadas sob as categorias pai, permitindo relatórios com "drill-down".
