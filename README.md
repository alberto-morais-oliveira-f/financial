# Módulo Financeiro para Laravel (am2tec/financial)

Um pacote robusto e agnóstico para Laravel que fornece uma base sólida para funcionalidades financeiras, inspirado em sistemas de General Ledger (Livro Razão). Projetado para ser desacoplado, extensível e testável.

## Princípios

- **Double-Entry Bookkeeping**: Toda transação gera duas ou mais entradas (débito e crédito), garantindo que o dinheiro nunca seja criado ou destruído, apenas movimentado.
- **Imutabilidade**: Transações confirmadas (`POSTED`) não são alteradas. Correções são feitas através de transações de estorno.
- **Agnóstico ao Domínio**: O pacote não conhece suas regras de negócio (usuários, produtos, etc.). A integração é feita via interfaces (`Contracts`).
- **Configurável e Extensível**: Permite a customização de moedas e a implementação de gateways de pagamento próprios.

---

## 1. Instalação

Instale o pacote via Composer:

```bash
composer require am2tec/financial
```

---

## 2. Configuração

1.  **Publicar Arquivos de Configuração e Migrations**:
    Este comando irá publicar o arquivo `config/financial.php` e as migrations do pacote.

    ```bash
    php artisan vendor:publish --provider="Am2tec\Financial\Infrastructure\Providers\FinancialServiceProvider" --tag="financial-config"
    php artisan vendor:publish --provider="Am2tec\Financial\Infrastructure\Providers\FinancialServiceProvider" --tag="financial-migrations"
    ```

2.  **Publicar Assets do Frontend**:
    Este comando irá publicar os arquivos de assets (CSS, JS, imagens) necessários para o funcionamento das interfaces do pacote.

    ```bash
    php artisan vendor:publish --provider="Am2tec\Financial\Infrastructure\Providers\FinancialServiceProvider" --tag="financial-assets"
    ```

3.  **Publicar Views (Opcional)**:
    Caso deseje customizar as views do pacote, você pode publicá-las para `resources/views/vendor/financial`.

    ```bash
    php artisan vendor:publish --provider="Am2tec\Financial\Infrastructure\Providers\FinancialServiceProvider" --tag="financial-views"
    ```

4.  **Executar as Migrations**:
    Execute as migrations para criar todas as tabelas necessárias, incluindo carteiras, transações e as novas tabelas de categorias.

    ```bash
    php artisan migrate
    ```

5.  **(Opcional) Popular Categorias Padrão**:
    Para começar rapidamente, você pode popular o banco de dados com um conjunto de categorias financeiras padrão (Receitas, Despesas, Custos, etc.).

    ```bash
    php artisan db:seed --class="Am2tec\Financial\Infrastructure\Persistence\Seeders\CategorySeeder"
    ```

---

## 3. Frontend e Assets

O pacote inclui uma interface administrativa construída com Bootstrap e Vite. Para garantir que os estilos e scripts sejam carregados corretamente na sua aplicação Laravel, siga os passos abaixo:

### a. Configuração do Vite

Se você estiver usando Vite na sua aplicação principal, certifique-se de que os assets do pacote publicados em `public/vendor/financial` sejam acessíveis. O pacote já fornece os arquivos compilados, então você geralmente não precisa recompilá-los, apenas referenciá-los nas suas views se estiver estendendo os layouts.

No entanto, as views do pacote já vêm configuradas para carregar os assets corretos.

### b. Dependências de Frontend

O pacote utiliza as seguintes bibliotecas (já incluídas nos assets compilados ou referenciadas via CDN/Vendor):
- Bootstrap 5
- jQuery
- DataTables (com integração BS5)
- Feather Icons
- Perfect Scrollbar

Se você precisar recompilar os assets do pacote (para desenvolvimento do próprio pacote), instale as dependências:

```bash
npm install
npm run build
```

---

## 4. Conceitos Principais

- **Wallet**: A carteira virtual de uma entidade. É o contêiner do saldo.
- **Transaction**: Representa uma movimentação financeira (ex: uma transferência). É composta por `Entries`.
- **Entry**: Uma perna da transação (um débito ou um crédito) em uma `Wallet`.
- **Category**: Uma etiqueta contábil para uma `Entry` (ex: "Receita", "Despesa"), usada para relatórios.
- **Payment**: Representa uma cobrança feita através de um gateway de pagamento.
- **Refund**: Representa o estorno (parcial ou total) de um `Payment`.
- **Title**: Uma obrigação financeira futura (uma conta a pagar ou a receber).
- **RecurringSchedule**: Uma regra para a criação automática de `Titles` em uma frequência definida (via Cron).

---

## 5. Guia de Uso

### a. Implementando o `AccountOwner`

Para que uma de suas models (como `User`) possa ter uma carteira, ela precisa implementar a interface `AccountOwner`.

```php
// app/Models/User.php
use Am2tec\Financial\Domain\Contracts\AccountOwner;

class User extends Authenticatable implements AccountOwner
{
    // ...

    public function getOwnerId(): string|int
    {
        return $this->id;
    }

    public function getOwnerType(): string
    {
        return self::class;
    }

    public function getOwnerName(): string
    {
        return $this->name;
    }

    public function getOwnerEmail(): ?string
    {
        return $this->email;
    }
}
```

### b. Criando uma Carteira

Use o `WalletService` para criar uma carteira para um `AccountOwner`.

```php
use Am2tec\Financial\Domain\Services\WalletService;

$user = User::find(1);
$walletService = app(WalletService::class);

$wallet = $walletService->createWallet($user, 'Minha Carteira Principal');

echo "Carteira criada com ID: " . $wallet->uuid;
```

### c. Realizando uma Transferência

Use o `TransactionService` para transferir valores entre duas carteiras. A categorização é feita no nível da transação ou das entradas individuais (Entries).

```php
use Am2tec\Financial\Domain\Services\TransactionService;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Domain\ValueObjects\Currency;

$transactionService = app(TransactionService::class);

$fromWalletId = 'uuid-da-carteira-origem';
$toWalletId = 'uuid-da-carteira-destino';
$amount = new Money(10000, new Currency('BRL')); // R$ 100,00
$revenueCategoryId = 'uuid-da-categoria-de-receita'; // Obtenha o ID da sua categoria

try {
    // Exemplo de categorização simples (a ser implementado no TransactionService)
    $transaction = $transactionService->transfer(
        $fromWalletId,
        $toWalletId,
        $amount,
        'Pagamento de serviço',
        ['category_uuid' => $revenueCategoryId] // Passando a categoria
    );
    echo "Transferência realizada! ID da Transação: " . $transaction->uuid;
} catch (\DomainException $e) {
    echo "Erro: " . $e->getMessage();
}
```

### d. Processando Pagamentos (Requer Implementação)

Para processar pagamentos, você **precisa** criar sua própria implementação da interface `PaymentGatewayAdapter` e registrá-la no `AppServiceProvider` da sua aplicação.

**1. Crie seu Adaptador:**

```php
// app/Adapters/MyStripeAdapter.php
use Am2tec\Financial\Domain\Contracts\PaymentGatewayAdapter;
// ...

class MyStripeAdapter implements PaymentGatewayAdapter
{
    public function charge(PaymentData $data): GatewayResponse
    {
        // Sua lógica para cobrar com o Stripe SDK
    }

    public function refund(string $gatewayTransactionId, ?Money $amount = null): GatewayResponse
    {
        // Sua lógica para estornar com o Stripe SDK
    }
}
```

**2. Registre o Binding no `AppServiceProvider`:**

```php
// app/Providers/AppServiceProvider.php
use Am2tec\Financial\Domain\Contracts\PaymentGatewayAdapter;
use App\Adapters\MyStripeAdapter;

public function register()
{
    $this->app->bind(PaymentGatewayAdapter::class, MyStripeAdapter::class);
}
```

**3. Use o `PaymentService`:**

```php
use Am2tec\Financial\Domain\Services\PaymentService;
use Am2tec\Financial\Application\DTOs\PaymentData;

$paymentService = app(PaymentService::class);
$paymentData = new PaymentData(/* ... */);

$payment = $paymentService->charge($paymentData);
```

### e. Realizando um Estorno

Use o `PaymentService` para estornar um pagamento (parcial ou total).

```php
use Am2tec\Financial\Domain\Services\PaymentService;

$paymentService = app(PaymentService::class);
$paymentId = 'uuid-do-pagamento-original';
$amountToRefund = new Money(5000, new Currency('BRL')); // R$ 50,00

$refund = $paymentService->refund($paymentId, $amountToRefund, 'Cliente solicitou estorno parcial');

echo "Estorno processado! ID: " . $refund->uuid;
```

### f. Agendamentos (Recorrência)

Para criar cobranças recorrentes, crie um `RecurringSchedule`.

```php
use Am2tec\Financial\Domain\Entities\RecurringSchedule;
use Am2tec\Financial\Domain\Contracts\RecurringScheduleRepositoryInterface;

$repository = app(RecurringScheduleRepositoryInterface::class);

$schedule = new RecurringSchedule(
    uuid: \Illuminate\Support\Str::uuid()->toString(),
    walletId: 'uuid-da-carteira-a-ser-cobrada',
    amount: new Money(2990, new Currency('BRL')),
    cronExpression: '0 0 5 * *', // Todo dia 5 de cada mês
    description: 'Cobrança Mensal de Assinatura',
    nextRunAt: new \DateTimeImmutable('first day of next month')
);

$repository->save($schedule);
```

Para processar os agendamentos, execute o comando Artisan. Você deve agendar isso no `Kernel` da sua aplicação para rodar diariamente.

```bash
php artisan financial:process-recurring
```

### g. Ouvindo Eventos

O pacote dispara eventos para que sua aplicação possa reagir a eles.

**Exemplo: Ouvindo um pagamento recebido**

No seu `EventServiceProvider`, registre um Listener:

```php
// app/Providers/EventServiceProvider.php
protected $listen = [
    \Am2tec\Financial\Domain\Events\PaymentReceived::class => [
        \App\Listeners\GrantAccessToCourse::class,
    ],
];
```

Crie o Listener:

```php
// app/Listeners/GrantAccessToCourse.php
use Am2tec\Financial\Domain\Events\PaymentReceived;

class GrantAccessToCourse
{
    public function handle(PaymentReceived $event)
    {
        $payment = $event->payment;
        // Sua lógica aqui: liberar acesso, enviar e-mail, etc.
    }
}
```

### h. Caso de Uso Avançado: Split de Pagamento (Marketplace)

A arquitetura de `Transaction` com múltiplas `Entries` permite cenários complexos como o *split* de pagamentos. A categorização aqui é fundamental.

**Cenário:** Um cliente compra um produto de R$ 100,00. A plataforma fica com uma comissão de 10% (R$ 10,00) e o vendedor recebe o restante (R$ 90,00).

1.  **Carteiras e Categorias Envolvidas:**
    *   `platformRevenueWallet`: Carteira da plataforma.
    *   `sellerWallet`: Carteira do vendedor.
    *   `customerWallet`: Carteira do cliente.
    *   `salesRevenueCategory`: Categoria de "Receita de Vendas".
    *   `commissionRevenueCategory`: Categoria de "Receita de Comissão".
    *   `transferAssetCategory`: Categoria de "Transferência entre contas".

2.  **Lógica de Negócio (Sua Aplicação):**

```php
use Am2tec\Financial\Domain\Services\TransactionService;
use Am2tec\Financial\Application\DTOs\EntryData;
use Am2tec\Financial\Domain\Enums\EntryType;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Domain\ValueObjects\Currency;

// --- 1. Lógica de negócio da sua aplicação ---
$totalAmount = new Money(10000, new Currency('BRL'));
$commissionAmount = $totalAmount->multiply(0.10);
$sellerAmount = $totalAmount->subtract($commissionAmount);

// --- 2. Montar as "pernas" da transação com categorias ---
$entries = [
    // Débito: Sai R$ 100,00 da carteira do cliente
    new EntryData(
        walletId: $customerWalletId,
        type: EntryType::DEBIT,
        amount: $totalAmount,
        categoryId: $salesRevenueCategory->uuid // Categoria da venda
    ),
    // Crédito: Entra R$ 90,00 na carteira do vendedor
    new EntryData(
        walletId: $sellerWalletId,
        type: EntryType::CREDIT,
        amount: $sellerAmount,
        categoryId: $transferAssetCategory->uuid // Recebimento do vendedor
    ),
    // Crédito: Entra R$ 10,00 na carteira da plataforma
    new EntryData(
        walletId: $platformWalletId,
        type: EntryType::CREDIT,
        amount: $commissionAmount,
        categoryId: $commissionRevenueCategory->uuid // Receita de comissão
    ),
];

// --- 3. Instruir o módulo financeiro para executar a transação ---
try {
    $transactionService = app(TransactionService::class);
    $transaction = $transactionService->createTransaction(
        $entries,
        'Venda do produto XYZ - Pedido #123'
    );

    echo "Transação de split realizada com sucesso! ID: " . $transaction->uuid;
} catch (\DomainException $e) {
    echo "Erro na transação de split: " . $e->getMessage();
}
```

---

## 6. API REST

O pacote expõe alguns endpoints de API. Eles são protegidos por Policies, então o usuário precisa estar autenticado.

-   `GET /api/financial/wallets/{id}`: Detalhes de uma carteira.
-   `POST /api/financial/transactions/transfer`: Realiza uma transferência.
-   `POST /api/financial/payments/{id}/refund`: Processa um estorno.
-   `POST /api/financial/webhooks/{gateway}`: Endpoint para receber webhooks de gateways.

**Exemplo de Request para Transferência:**

```json
// POST /api/financial/transactions/transfer
{
    "from_wallet_id": "...",
    "to_wallet_id": "...",
    "amount": 1000,
    "description": "Pagamento via API",
    "category_id": "uuid-da-categoria"
}
```
