# Documentação da API - Módulo Financeiro

Esta documentação descreve os endpoints disponíveis no módulo financeiro, seus parâmetros e formatos de resposta.

## Base URL
`/api` (ou conforme configurado no projeto consumidor)

---

## 1. Carteiras (Wallets)

### Obter Detalhes da Carteira
Retorna informações sobre uma carteira específica, incluindo saldo atual.

**Endpoint:** `GET /wallets/{id}`

**Parâmetros de URL:**
*   `id` (string, required): UUID da carteira.

**Resposta de Sucesso (200 OK):**
```json
{
  "id": "uuid-da-carteira",
  "owner_id": "id-do-dono",
  "owner_type": "App\\Models\\User",
  "name": "Carteira Principal",
  "balance": 1000, // Valor em centavos (inteiro)
  "currency": "BRL",
  "status": "active"
}
```

**Erros Possíveis:**
*   `404 Not Found`: Carteira não encontrada.
*   `403 Forbidden`: Usuário não tem permissão para visualizar esta carteira.

---

## 2. Transações (Transactions)

### Realizar Transferência
Transfere valores entre duas carteiras internas.

**Endpoint:** `POST /transactions/transfer`

**Body (JSON):**
```json
{
  "from_wallet_id": "uuid-origem",
  "to_wallet_id": "uuid-destino",
  "amount": 5000, // Valor em centavos (R$ 50,00)
  "description": "Pagamento de serviços"
}
```

**Resposta de Sucesso (200 OK):**
```json
{
  "id": "uuid-da-transacao",
  "status": "posted",
  "description": "Pagamento de serviços",
  "entries": [
    {
      "wallet_id": "uuid-origem",
      "type": "debit",
      "amount": 5000
    },
    {
      "wallet_id": "uuid-destino",
      "type": "credit",
      "amount": 5000
    }
  ]
}
```

**Erros Possíveis:**
*   `422 Unprocessable Entity`: Saldo insuficiente ou dados inválidos.
*   `403 Forbidden`: Sem permissão para debitar da carteira de origem.

---

## 3. Pagamentos (Payments)

### Estornar Pagamento (Refund)
Realiza o estorno total ou parcial de um pagamento processado anteriormente.

**Endpoint:** `POST /payments/{id}/refund`

**Parâmetros de URL:**
*   `id` (string, required): UUID do pagamento original.

**Body (JSON):**
```json
{
  "amount": 5000, // Valor a estornar em centavos
  "currency": "BRL",
  "reason": "Cliente solicitou cancelamento"
}
```

**Resposta de Sucesso (200 OK):**
```json
{
  "id": "uuid-do-estorno",
  "payment_id": "uuid-do-pagamento-original",
  "amount": 5000,
  "currency": "BRL",
  "status": "completed", // ou 'pending' dependendo do gateway
  "reason": "Cliente solicitou cancelamento",
  "created_at": "2023-10-27 10:00:00"
}
```

**Erros Possíveis:**
*   `422 Unprocessable Entity`: Valor maior que o disponível para estorno.

---

## 4. Relatórios (Reports)

### Demonstrativo de Resultados (DRE)
Gera um relatório simplificado de receitas e despesas por período.

**Endpoint:** `GET /reports/dre`

**Query Parameters:**
*   `start_date` (string, required): Data inicial (YYYY-MM-DD).
*   `end_date` (string, required): Data final (YYYY-MM-DD).

**Resposta de Sucesso (200 OK):**
```json
{
  "revenue": 150000,
  "expenses": 50000,
  "net_income": 100000,
  "period": {
    "start": "2023-01-01",
    "end": "2023-01-31"
  }
}
```
*(Nota: A estrutura exata do retorno depende da implementação do `DreService`, este é um exemplo genérico baseado no controller)*

---

## 5. Webhooks

### Receber Notificação de Gateway
Endpoint público para receber callbacks de gateways de pagamento (Stripe, Pagar.me, etc).

**Endpoint:** `POST /webhooks/{gateway}`

**Parâmetros de URL:**
*   `gateway` (string, required): Nome do driver (ex: `stripe`, `pagarme`).

**Body (JSON):**
*   Payload variável conforme a documentação do gateway específico.

**Resposta de Sucesso:**
```json
{
  "status": "received"
}
```

---

## 6. Categorias (Categories)

### Listar Categorias
Retorna uma lista de todas as categorias financeiras.

**Endpoint:** `GET /categories`

**Resposta de Sucesso (200 OK):**
```json
{
  "data": [
    {
      "uuid": "uuid-da-categoria-1",
      "parent_uuid": null,
      "name": "Receitas",
      "slug": "receitas",
      "type": "REVENUE",
      "description": "Receitas provenientes da atividade principal."
    },
    {
      "uuid": "uuid-da-categoria-2",
      "parent_uuid": "uuid-da-categoria-1",
      "name": "Receita de Vendas",
      "slug": "receita-de-vendas",
      "type": "REVENUE",
      "description": "Receita proveniente da venda de produtos."
    }
  ]
}
```
