# Design de Relatório: DFC (Demonstração do Fluxo de Caixa) - Consolidado e por Métodos

Este documento detalha a estratégia técnica para gerar a DFC, com foco na **consolidação de múltiplas carteiras de caixa** e na apresentação pelos métodos Direto e Indireto.

## 1. Conceito de DFC Consolidada

Uma DFC consolidada apresenta o fluxo de caixa total de uma entidade como se todas as suas contas de caixa (`Wallets` do tipo `CASH`) fossem uma só.

-   **Diferença Chave**: Uma DFC por *wallet* mostra entradas e saídas de uma única conta. Uma DFC *consolidada* mostra as entradas e saídas da entidade com o **mundo externo**.
-   **Risco de Dupla Contagem**: O principal risco ao somar DFCs individuais é inflar os números com **transferências internas**. Uma transferência de R$1.000 do "Banco A" para o "Caixa Físico" apareceria como uma saída de R$1.000 e uma entrada de R$1.000, poluindo o relatório com um fluxo que não representa uma interação com terceiros.
-   **Critério de Consolidação**: O objetivo é **eliminar** os lançamentos que representam transferências internas entre carteiras de caixa, computando apenas os fluxos de/para o ambiente externo.

---

## 2. Método Direto Consolidado (A Abordagem Padrão)

O método direto é o mais adequado para a consolidação, pois permite a análise de cada transação.

### a. Identificação de Transferências Internas

Uma transação é uma transferência interna de caixa se **todas** as suas `Entries` (lançamentos) ocorrem exclusivamente em `Wallets` do tipo `CASH`.

-   **Exemplo**: Transferência de R$500 do "Banco A" para o "Banco B".
    -   `Transaction` #123
        -   `Entry` 1: **Crédito** de R$500 na `Wallet` "Banco A" (type: `CASH`)
        -   `Entry` 2: **Débito** de R$500 na `Wallet` "Banco B" (type: `CASH`)
    -   Esta transação deve ser **excluída** da DFC consolidada.

-   **Caso Especial**: Uma transação que envolve uma `Wallet` de caixa e uma `Wallet` não-caixa **NÃO** é uma transferência interna e **DEVE** ser incluída.
    -   **Exemplo**: Pagamento de uma fatura.
        -   `Transaction` #124
            -   `Entry` 1: **Crédito** de R$200 na `Wallet` "Banco A" (type: `CASH`) -> **Saída de Caixa**
            -   `Entry` 2: **Débito** de R$200 na `Wallet` "Contas a Pagar" (type: `VIRTUAL`)
    -   Esta é uma saída de caixa real para o mundo externo.

### b. Query Conceitual (SQL) para DFC Consolidada

A estratégia consiste em duas etapas:
1.  Identificar os IDs de todas as transações que são puramente internas.
2.  Executar a query de fluxo de caixa, excluindo essas transações.

**Passo 1: Identificar Transações Internas (Subquery)**

```sql
-- Seleciona IDs de transações onde NENHUMA entry toca uma wallet não-caixa.
SELECT t.uuid
FROM financial_transactions t
WHERE
    -- Verifica se não existe (NOT EXISTS) nenhuma entry na transação
    -- que esteja ligada a uma wallet que NÃO seja do tipo 'CASH'.
    NOT EXISTS (
        SELECT 1
        FROM financial_entries e
        JOIN financial_wallets w ON e.wallet_id = w.uuid
        WHERE e.transaction_id = t.uuid AND w.type != 'CASH'
    )
    -- Garante que a transação tenha pelo menos uma entry (consistência)
    AND EXISTS (
        SELECT 1 FROM financial_entries e WHERE e.transaction_id = t.uuid
    )
```

**Passo 2: Query Principal Excluindo Transferências**

```sql
SELECT
    SUM(CASE WHEN e.type = 'debit' THEN e.amount ELSE 0 END) AS total_inflows,
    SUM(CASE WHEN e.type = 'credit' THEN e.amount ELSE 0 END) AS total_outflows
FROM
    financial_entries AS e
JOIN
    financial_wallets AS w ON e.wallet_id = w.uuid
JOIN
    financial_transactions AS t ON e.transaction_id = t.uuid
WHERE
    t.status = 'POSTED'
    AND w.type = 'CASH'
    AND t.updated_at BETWEEN :start_date AND :end_date
    -- A lógica de exclusão:
    AND t.uuid NOT IN (
        -- Subquery do Passo 1
        SELECT t_inner.uuid
        FROM financial_transactions t_inner
        WHERE NOT EXISTS (
            SELECT 1
            FROM financial_entries e_inner
            JOIN financial_wallets w_inner ON e_inner.wallet_id = w_inner.uuid
            WHERE e_inner.transaction_id = t_inner.uuid AND w_inner.type != 'CASH'
        )
        AND EXISTS (
            SELECT 1 FROM financial_entries e_inner WHERE e_inner.transaction_id = t_inner.uuid
        )
    );
```

### c. Query em Laravel (Query Builder)

```php
use Illuminate\Support\Facades\DB;

$startDate = '2024-01-01 00:00:00';
$endDate = '2024-01-31 23:59:59';

$entriesTable = config('financial.table_prefix', 'fin_') . 'entries';
$walletsTable = config('financial.table_prefix', 'fin_') . 'wallets';
$transactionsTable = config('financial.table_prefix', 'fin_') . 'transactions';

// Subquery para encontrar IDs de transações puramente internas
$internalTransactions = DB::table("{$transactionsTable} as t_inner")
    ->whereNotExists(function ($query) use ($entriesTable, $walletsTable) {
        $query->select(DB::raw(1))
              ->from("{$entriesTable} as e_inner")
              ->join("{$walletsTable} as w_inner", 'e_inner.wallet_id', '=', 'w_inner.uuid')
              ->whereColumn('e_inner.transaction_id', 't_inner.uuid')
              ->where('w_inner.type', '!=', 'CASH');
    })
    ->whereExists(function ($query) use ($entriesTable) {
        $query->select(DB::raw(1))
              ->from("{$entriesTable} as e_inner")
              ->whereColumn('e_inner.transaction_id', 't_inner.uuid');
    })
    ->pluck('t_inner.uuid');

// Query principal que exclui as transações internas
$cashFlow = DB::table("{$entriesTable} as e")
    ->join("{$walletsTable} as w", 'e.wallet_id', '=', 'w.uuid')
    ->join("{$transactionsTable} as t", 'e.transaction_id', '=', 't.uuid')
    ->selectRaw("
        SUM(CASE WHEN e.type = 'debit' THEN e.amount ELSE 0 END) AS total_inflows,
        SUM(CASE WHEN e.type = 'credit' THEN e.amount ELSE 0 END) AS total_outflows
    ")
    ->where('t.status', 'POSTED')
    ->where('w.type', 'CASH')
    ->whereBetween('t.updated_at', [$startDate, $endDate])
    ->whereNotIn('t.uuid', $internalTransactions) // Exclusão
    ->first();

$netCashFlow = ($cashFlow->total_inflows ?? 0) - ($cashFlow->total_outflows ?? 0);
```

### d. Estrutura do Resultado

O resultado é uma estrutura consolidada e limpa:
```json
{
    "total_inflows": 15000000,  // Apenas entradas externas
    "total_outflows": 8000000, // Apenas saídas externas
    "net_cash_flow": 7000000
}
```

---

## 3. Método Indireto Consolidado

A lógica do método indireto não muda com a consolidação, pois ela já parte de uma visão consolidada (Lucro Líquido da DRE). Os ajustes, como a variação de Contas a Receber/Pagar, também são calculados sobre totais consolidados. A eliminação de transferências internas é **inerente** ao método indireto, pois essas transferências não afetam o lucro nem as contas de balanço operacionais externas.

---

## 4. Casos Especiais e Anti-Padrões

-   **Multi-Moeda**: Se o sistema suportar múltiplas moedas, a consolidação exige um passo adicional: converter todos os valores para uma única moeda de relatório (`reporting currency`) usando uma taxa de câmbio apropriada (média ou de fechamento) antes da agregação. A query precisaria de um join com uma tabela de taxas de câmbio.
-   **Multi-Tenant**: Em um ambiente multi-tenant, todas as queries devem ser rigorosamente escopadas pelo `owner_id` ou `tenant_id` da entidade (empresa) para evitar vazamento de dados.
-   **Anti-Padrão: Ignorar a Contrapartida**: Uma abordagem ingênua seria simplesmente somar débitos e créditos em contas de caixa. Isso falha ao não eliminar as transferências internas, inflando os totais e fornecendo uma imagem falsa da geração de caixa.
-   **Anti-Padrão: Somar Saldos**: A DFC é sobre **fluxo**, não sobre **saldo**. Calcular a DFC pela diferença de `SUM(wallet.balance)` entre duas datas é incorreto, pois não revela as entradas e saídas brutas e não elimina transferências internas.
