# Arquitetura do Módulo Financeiro

Este documento define o escopo arquitetural e a modelagem de domínio para o pacote financeiro genérico.

## 1. Escopo do Módulo

### Objetivo Principal
Prover uma estrutura de **Livro Razão (General Ledger)** e processamento de transações que garanta a integridade, rastreabilidade e imutabilidade dos dados financeiros, servindo como a "fonte da verdade" para saldos e movimentações, independentemente da origem da receita ou despesa.

### O que o módulo DEVE fazer (Core Capabilities)
*   **Gestão de Carteiras/Contas (Wallets):** Criar e gerenciar contas virtuais associadas a entidades polimórficas.
*   **Registro de Transações (Double-Entry):** Registrar créditos, débitos e transferências entre contas internas.
*   **Controle de Saldos:** Calcular e persistir saldos de forma atômica.
*   **Abstração de Pagamentos:** Interfaces padronizadas para Gateways (Pending, Paid, Failed, Refunded).
*   **Contas a Pagar e Receber:** Gerenciar lançamentos futuros (agendamentos e vencimentos).
*   **Conciliação:** Permitir marcação de transações conciliadas.
*   **Trilha de Auditoria:** Registrar metadados de contexto.
*   **Matemática Segura:** Cálculos utilizando inteiros ou precisão arbitrária.

### O que o módulo NÃO DEVE fazer (Out of Scope)
*   **Regras de Negócio do Domínio:** Não decide preços ou permissões de compra.
*   **Cálculo de Impostos Complexos:** Não contém lógica fiscal específica de nichos.
*   **Gestão de Estoque ou Produtos:** Desconhece itens, apenas valores e descrições.
*   **Autenticação de Usuários:** Não gerencia login.
*   **Geração de Documentos Fiscais (NF-e):** Apenas armazena referências, não emite notas.

### Princípios Arquiteturais
*   **Imutabilidade:** Transações efetivadas nunca são editadas ou deletadas (apenas estornadas).
*   **Persistence Ignorance:** Acesso a dados via Repository Pattern.
*   **Bounded Context (DDD):** Comunicação externa via Eventos ou DTOs.
*   **Polimorfismo:** "Dono" da conta é uma interface.
*   **API First (Internal):** Comunicação via Services e DTOs.

### Limites Explícitos (Bounded Context)

| Entrada (Input) | Fronteira | Saída (Output) |
| :--- | :---: | :--- |
| "Cobrar R$ 100,00" | **PROCESSAMENTO** | "Transação #999 PENDING" |
| "Estornar #999" | **LÓGICA** | "Transação #1000 (Estorno)" |
| "Saldo?" | **CONSULTA** | "R$ 50,00 (DTO)" |

---

## 2. Modelagem de Domínio (DDD)

### Entidades Principais

#### 1. Wallet (Carteira) - *Aggregate Root*
*   **Responsabilidade:** Contêiner de valor e unidade atômica de saldo.
*   **Atributos:** UUID, Owner (Polimórfico), Currency, Balance, Type, Status.

#### 2. Transaction (Transação) - *Aggregate Root*
*   **Responsabilidade:** Evento financeiro atômico (Double-Entry). Garante `Sum(Debits) == Sum(Credits)`.
*   **Atributos:** UUID, ReferenceCode, Description, Status (Pending, Posted, Failed), Dates, Metadata.

#### 3. Entry (Lançamento) - *Entity*
*   **Responsabilidade:** Perna específica da movimentação (Débito ou Crédito).
*   **Atributos:** WalletID, Type (Debit/Credit), Amount, BeforeBalance, AfterBalance.

#### 4. Payment (Pagamento) - *Aggregate Root*
*   **Responsabilidade:** Interação com Gateways de Pagamento.
*   **Atributos:** UUID, Gateway, GatewayTransactionID, Method, Amount, Status.

#### 5. Receivable / Payable (Títulos) - *Aggregate Root*
*   **Responsabilidade:** Obrigação financeira futura (Contas a Receber/Pagar).
*   **Atributos:** UUID, WalletID, Amount, DueDate, Status, Payer/Payee Snapshot.

### Value Objects
*   **Money:** Amount (BigInt) + Currency.
*   **Currency:** Código ISO e precisão.
*   **TransactionType:** Enum (Deposit, Withdrawal, Transfer, Payment, Refund).
*   **EntryType:** Enum (Debit, Credit).
*   **PaymentMethod:** Detalhes do método (tipo, last4, brand).

### Relacionamentos Chave
*   **Wallet (1) <-> (N) Entry**
*   **Transaction (1) <-> (N) Entry** (Mínimo 2 entries por transaction)
*   **Payment (1) <-> (0..1) Transaction**
*   **Receivable/Payable (1) <-> (0..N) Transaction**

### Eventos de Domínio
*   `WalletBalanceUpdated`
*   `TransactionPosted`
*   `PaymentStatusChanged`
*   `ReceivablePaid`
*   `PayableOverdue`

---

## 3. Contratos e Extensibilidade

Para garantir o desacoplamento e a reutilização, o módulo expõe interfaces que devem ser implementadas pelo sistema consumidor ou por adaptadores.

### Interfaces Principais (Contracts)

#### 1. `AccountOwner`
*   **Responsabilidade:** Define qualquer entidade do sistema consumidor que possa possuir uma carteira (User, Company, Tenant).
*   **Métodos Esperados:**
    *   `getOwnerId(): string|int`
    *   `getOwnerType(): string`
    *   `getOwnerName(): string` (para fins de exibição/auditoria)
    *   `getOwnerEmail(): string` (opcional, para notificações)

#### 2. `PaymentGatewayAdapter`
*   **Responsabilidade:** Abstrai a comunicação com provedores de pagamento externos (Stripe, Pagar.me, PayPal).
*   **Métodos Esperados:**
    *   `charge(PaymentData $data): GatewayResponse`
    *   `refund(string $gatewayTransactionId, ?Money $amount): GatewayResponse`
    *   `subscribe(SubscriptionData $data): SubscriptionResponse`
    *   `webhook(array $payload): WebhookEvent`

#### 3. `CurrencyResolver`
*   **Responsabilidade:** Determina qual moeda deve ser usada em um contexto específico, caso o sistema seja multi-moeda.
*   **Métodos Esperados:**
    *   `resolveFor(AccountOwner $owner): Currency`

### Estratégia de Inversão de Controle (IoC)

O módulo não instancia classes concretas diretamente. Ele depende de interfaces injetadas via Service Container do Laravel.

1.  **Binding no Service Provider:** O pacote fornece um `FinancialServiceProvider` que registra as implementações padrão.
2.  **Configuração:** O usuário do pacote define no arquivo `config/financial.php` quais classes concretas devem ser usadas para cada interface.
    *   Ex: `'gateway' => App\Services\StripeAdapter::class`
3.  **Factories:** O módulo utiliza Factories para instanciar os adaptadores corretos baseados na configuração ou contexto.

### Evitando Acoplamento entre Domínios

*   **Eventos como Fronteira:** O módulo financeiro emite eventos (`TransactionPosted`) que o sistema consumidor escuta para disparar suas próprias regras (ex: liberar acesso ao curso). O financeiro não chama `CourseService->unlock()`.
*   **DTOs (Data Transfer Objects):** Toda entrada e saída de dados é feita via DTOs imutáveis, nunca passando Models do Eloquent diretamente para dentro dos Services do pacote.
*   **Polimorfismo via Interface:** O relacionamento com `users` ou `companies` é feito via `morphTo` no banco, mas no código é tratado estritamente como `AccountOwner`.

### Exemplos Conceituais de Integração

1.  **Cenário: Assinatura de SaaS**
    *   Sistema Consumidor: Detecta novo cadastro.
    *   Sistema Consumidor: Chama `WalletService->createWallet($user)`.
    *   Sistema Consumidor: Cobra cartão via `PaymentService->charge($dto)`.
    *   Módulo Financeiro: Processa pagamento, cria transação, emite evento `PaymentApproved`.
    *   Sistema Consumidor (Listener): Escuta `PaymentApproved` e ativa o plano do usuário.

2.  **Cenário: Marketplace (Split de Pagamento)**
    *   Sistema Consumidor: Recebe pedido com múltiplos vendedores.
    *   Sistema Consumidor: Chama `TransactionService->transfer()` para mover saldo da carteira "Transitória" para as carteiras dos vendedores, descontando a comissão da plataforma.

---

## 4. Estrutura do Pacote e Organização

A estrutura de diretórios segue uma adaptação da Arquitetura Hexagonal (Ports and Adapters) para o contexto de pacotes Laravel, visando separar o Domínio da Infraestrutura.

### Estrutura de Diretórios

```
src/
 ├── Domain/                  # O Núcleo do Negócio (Puro PHP, sem dependências de framework se possível)
 │   ├── Entities/            # Modelos ricos (Wallet, Transaction)
 │   ├── ValueObjects/        # Objetos imutáveis (Money, Currency)
 │   ├── Events/              # Eventos de Domínio (TransactionPosted)
 │   ├── Contracts/           # Interfaces (Ports) para Repositórios e Gateways
 │   ├── Services/            # Serviços de Domínio (TransactionService)
 │   └── Exceptions/          # Exceções específicas do domínio
 ├── Application/             # Camada de Aplicação (Orquestração)
 │   ├── UseCases/            # Casos de uso específicos (CreateWalletUseCase)
 │   └── DTOs/                # Data Transfer Objects para entrada/saída
 ├── Infrastructure/          # Implementação Técnica (Adapters)
 │   ├── Persistence/         # Implementações de Repositórios (Eloquent)
 │   │   ├── Models/          # Eloquent Models (mapeamento para BD)
 │   │   └── Repositories/    # Implementação concreta dos Contracts
 │   ├── Providers/           # Service Providers do Laravel
 │   ├── Http/                # Controladores e Rotas (Opcional/API)
 │   │   ├── Controllers/
 │   │   ├── Requests/        # FormRequests
 │   │   └── Resources/       # API Resources
 │   └── Listeners/           # Listeners de eventos internos
 ├── Support/                 # Helpers e utilitários gerais
 └── Config/                  # Arquivos de configuração padrão
```

### Responsabilidades Detalhadas

#### Domain (Núcleo)
*   Contém a lógica de negócio pura.
*   **Entities:** Classes que representam os conceitos do negócio. Podem ser POPOs (Plain Old PHP Objects) ou estender classes base abstratas, mas idealmente não estendem `Illuminate\Database\Eloquent\Model` diretamente para evitar acoplamento forte com o ORM nas regras de negócio, embora em pacotes Laravel seja comum usar o Eloquent Model como Entity por pragmatismo (Active Record). *Decisão: Usaremos Repository Pattern para abstrair a persistência, mas os Models do Eloquent residirão em Infrastructure.*
*   **Contracts:** Interfaces que definem o que o domínio precisa (ex: `WalletRepositoryInterface`).

#### Application (Aplicação)
*   Ponto de entrada para o uso do pacote.
*   **DTOs:** Definem a estrutura exata dos dados necessários para executar uma ação.
*   **UseCases:** Classes de ação única (ex: `ProcessPayment`) que orquestram o domínio.

#### Infrastructure (Infraestrutura)
*   Onde o Laravel "acontece".
*   **Persistence:** Aqui ficam os Models do Eloquent (`WalletModel`) e as Migrations. Os Repositórios convertem Models em Entidades de Domínio (se optarmos por separação estrita) ou retornam os próprios Models (abordagem pragmática Laravel).
*   **Providers:** `FinancialServiceProvider` registra os bindings, rotas e configurações.

### Configuração e Extensibilidade

#### Service Provider (`FinancialServiceProvider`)
*   **Boot:** Carrega migrations, publica arquivos de config, registra rotas e comandos.
*   **Register:** Faz o bind das interfaces (`Contracts`) para as implementações concretas (`Infrastructure`).

#### Configuração (`config/financial.php`)
*   Define drivers padrão (ex: qual Gateway usar).
*   Define prefixos de tabelas (ex: `fin_wallets`).
*   Define mapeamento de Models (permite que o usuário estenda os Models padrão).

#### Migrations
*   As migrations devem verificar se a tabela já existe para evitar conflitos.
*   Devem usar o prefixo definido na configuração.
*   Publicáveis via `php artisan vendor:publish`.

### Versionamento e Compatibilidade
*   **SemVer:** Versionamento Semântico rigoroso.
*   **Requisitos:** Definir versão mínima do PHP e Laravel no `composer.json`.
*   **Testes:** CI/CD rodando testes em múltiplas versões do Laravel para garantir compatibilidade.

---

## 5. Estratégia de Eventos e Automação

A arquitetura orientada a eventos (EDA) é fundamental para manter o módulo desacoplado. O módulo emite eventos sobre o que aconteceu (passado) e pode escutar eventos genéricos para reagir.

### Tipos de Eventos

#### 1. Eventos Emitidos pelo Módulo (Outbound)
Estes eventos notificam o mundo externo sobre mudanças de estado no domínio financeiro.
*   `TransactionCreated`: Transação iniciada, mas ainda pendente.
*   `TransactionPosted`: Transação efetivada com sucesso (saldo atualizado).
*   `TransactionFailed`: Transação falhou (ex: saldo insuficiente).
*   `WalletBalanceLow`: Saldo da carteira atingiu um limiar mínimo (configurável).
*   `PaymentReceived`: Pagamento confirmado pelo gateway.
*   `PaymentRefunded`: Pagamento estornado.

#### 2. Eventos Escutados pelo Módulo (Inbound)
O módulo pode ser configurado para escutar eventos do sistema consumidor, desde que mapeados corretamente.
*   **Abordagem:** O módulo não deve escutar `UserRegistered` diretamente, pois isso acopla ao domínio `User`.
*   **Solução:** O sistema consumidor deve criar um Listener próprio que escuta `UserRegistered` e chama o `CreateWalletUseCase` do módulo financeiro.
*   **Exceção:** Webhooks de Gateways de Pagamento são eventos externos que o módulo sabe tratar diretamente via `WebhookController`.

### Fluxo de Automação e Reatividade

#### Geração Automática de Lançamentos
O módulo não "adivinha" quando cobrar. O fluxo deve ser explícito:

1.  **Gatilho Externo:** Ocorre algo no sistema consumidor (ex: `OrderPlaced`).
2.  **Tradução:** Um Listener no sistema consumidor traduz `OrderPlaced` para um DTO `CreateTransactionDTO`.
3.  **Execução:** O Listener invoca o `TransactionService` do pacote.
4.  **Confirmação:** O pacote emite `TransactionPosted`.
5.  **Reação:** O sistema consumidor escuta `TransactionPosted` para atualizar o status do pedido para "Pago".

### Estratégia de Listeners e Subscribers

*   **Event Discovery:** O pacote registra seus eventos no `EventServiceProvider` interno, mas não registra Listeners para eventos da aplicação principal (para evitar invasão).
*   **Filas (Queues):** Todos os eventos emitidos devem implementar `ShouldQueue` para não bloquear a requisição HTTP principal, garantindo performance.

### Idempotência e Falhas

*   **Idempotency Key:** Toda operação de criação de transação deve aceitar uma chave de idempotência (ex: UUID do Pedido). Se o módulo receber uma segunda requisição com a mesma chave, deve retornar a transação original sem duplicar o lançamento.
*   **Atomicidade:** Listeners que realizam múltiplas operações financeiras devem envolver tudo em uma `DB::transaction`.
*   **Dead Letter Queue (DLQ):** Se um processamento de evento falhar após N tentativas, ele vai para uma tabela de falhas para análise manual, nunca descartado silenciosamente.

### Versionamento de Eventos

*   **Estrutura Estável:** Os eventos devem conter apenas dados primitivos ou DTOs serializáveis, nunca Models completos (que podem mudar de estrutura).
*   **Schema Registry (Conceitual):** Manter a estrutura do payload do evento documentada. Se precisar mudar drasticamente, criar `TransactionPostedV2`.

---

## 6. API REST e Integração Externa

O pacote pode expor opcionalmente uma API REST para ser consumida por frontends (SPA/Mobile) ou outros microsserviços.

### Princípios da API
*   **RESTful:** Uso correto de verbos HTTP (GET, POST, PUT, DELETE) e Status Codes.
*   **Stateless:** Nenhuma sessão é mantida no servidor entre requisições.
*   **Idempotência:** Métodos seguros (GET) e idempotentes (PUT, DELETE) garantidos. POST deve usar `Idempotency-Key` no header.
*   **JSON:API:** Respostas padronizadas seguindo a especificação JSON:API ou o padrão `Spatie\LaravelData`.

### Recursos Principais (Endpoints)

#### 1. Wallets (`/api/financial/wallets`)
*   `GET /` - Listar carteiras (com filtros).
*   `POST /` - Criar nova carteira.
*   `GET /{uuid}` - Detalhes e saldo atual.
*   `GET /{uuid}/statement` - Extrato (lista de transações).

#### 2. Transactions (`/api/financial/transactions`)
*   `POST /` - Criar nova transação (transferência, depósito, saque).
*   `GET /{uuid}` - Detalhes da transação.
*   `POST /{uuid}/refund` - Estornar transação.

#### 3. Payments (`/api/financial/payments`)
*   `POST /` - Iniciar pagamento (Checkout).
*   `GET /{uuid}` - Consultar status.

### Estratégia de Autenticação e Autorização
*   **Middleware:** As rotas são protegidas por um middleware configurável (`auth:sanctum` por padrão).
*   **Policies:** O pacote define Policies, mas permite que o sistema consumidor as sobrescreva.
    *   Ex: `WalletPolicy` verifica se `user->id === wallet->owner_id`.

### Versionamento da API
*   **URI Versioning:** `/api/v1/financial/...`
*   **Backward Compatibility:** Mudanças que quebram contratos exigem v2. Adição de campos é permitida na v1.

### Paginação, Filtros e Ordenação
*   **Paginação:** Cursor-based pagination para performance em grandes volumes de dados (extratos bancários).
*   **Filtros:** `?filter[status]=paid&filter[date_from]=2023-01-01`.
*   **Ordenação:** `?sort=-created_at`.

### Tratamento de Erros
*   Respostas de erro padronizadas (RFC 7807 Problem Details).
    ```json
    {
      "type": "about:blank",
      "title": "Insufficient Funds",
      "status": 422,
      "detail": "The wallet does not have enough balance for this transaction.",
      "instance": "/api/financial/transactions"
    }
    ```

### Segurança
*   **Rate Limiting:** Aplicado por IP ou User ID para evitar abuso.
*   **Input Validation:** FormRequests rigorosos para todos os inputs.
*   **Output Sanitization:** Resources garantem que dados sensíveis (ex: dados brutos do gateway) não vazem.

---

## 7. Estratégia de Recorrência

O módulo financeiro trata a recorrência como **Agendamentos Financeiros (Financial Schedules)**, desacoplando-se do conceito de "Assinatura" ou "Plano" do negócio.

### Conceito de Recorrência no Domínio Financeiro
Para o módulo, uma recorrência é apenas uma instrução para criar transações futuras repetidamente baseadas em um padrão temporal (Cron ou Intervalo).

### Entidade: `RecurringSchedule`
*   **Responsabilidade:** Armazenar a regra de repetição e o modelo da transação a ser gerada.
*   **Atributos:**
    *   `UUID`: Identificador único.
    *   `WalletID`: Carteira de origem/destino.
    *   `Template`: DTO contendo os dados da transação (valor, descrição, tipo).
    *   `CronExpression`: Padrão de repetição (ex: `0 0 1 * *` para todo dia 1º).
    *   `NextRunAt`: Data da próxima execução.
    *   `EndsAt`: Data limite (opcional).
    *   `Status`: Active, Paused, Cancelled, Completed.

### Responsabilidades do Módulo
1.  **Armazenar Definições:** Guardar *como* e *quando* cobrar.
2.  **Monitorar Vencimentos:** Identificar quais agendamentos devem rodar hoje.
3.  **Executar (Instanciar):** Criar a `Transaction` ou `Receivable` real baseada no template.
4.  **Atualizar Ponteiro:** Calcular e salvar o próximo `NextRunAt`.

### Fluxo de Geração Automática
1.  **Scheduler:** Um comando do Laravel (`financial:process-recurring`) roda a cada minuto/hora.
2.  **Query:** Busca `RecurringSchedules` onde `status=Active` e `next_run_at <= NOW()`.
3.  **Processamento (Job):** Para cada item encontrado, despacha um Job na fila.
4.  **Execução do Job:**
    *   Verifica se já existe transação para aquele período (evitar duplicidade).
    *   Cria a `Transaction` ou `Receivable`.
    *   Atualiza `next_run_at` baseada na expressão Cron.
    *   Emite evento `RecurringTransactionCreated`.

### Evitando Duplicidade
*   **Hash de Período:** O módulo gera um hash único combinando `ScheduleID + DataReferencia` para garantir que o job não crie duas cobranças para o mesmo mês se rodar duas vezes acidentalmente.

### Limites (O que NÃO faz)
*   **Gestão de Planos:** Não sabe se o plano "Gold" virou "Platinum". O sistema consumidor deve atualizar o `RecurringSchedule` com o novo valor.
*   **Retentativas de Cartão:** Isso é responsabilidade do módulo de Pagamentos (`Payment`), não da Recorrência. A recorrência apenas gera a ordem de cobrança (`Receivable`).
*   **Suspensão por Inadimplência:** O módulo continua gerando cobranças até que o sistema consumidor mude o status do `RecurringSchedule` para `Paused`.

---

## 8. Exemplos de Integração (Case Studies)

Esta seção demonstra como diferentes domínios de negócio podem consumir o módulo financeiro sem acoplamento.

### Caso A: Plataforma de Cursos Online (E-learning)

**Cenário:** O aluno compra um curso avulso e o acesso é liberado imediatamente após o pagamento.

1.  **Contratos Implementados:**
    *   `User` (Aluno) implementa `AccountOwner`.
2.  **Fluxo de Compra:**
    *   Frontend envia dados do cartão para API de Vendas.
    *   API de Vendas chama `PaymentService->charge($dto)` do módulo financeiro.
    *   Módulo Financeiro processa com Stripe, cria `Transaction` e emite evento `PaymentReceived`.
3.  **Reação do Domínio:**
    *   Listener `ReleaseCourseAccess` (no módulo de Cursos) escuta `PaymentReceived`.
    *   Listener verifica o `metadata['course_id']` da transação.
    *   Listener libera o acesso ao aluno.

### Caso B: Sistema de Gestão de Condomínios (Property Management)

**Cenário:** O sistema gera boletos mensais de condomínio para os moradores.

1.  **Contratos Implementados:**
    *   `ApartmentUnit` (Unidade) implementa `AccountOwner`.
2.  **Configuração da Recorrência:**
    *   Quando um morador entra, o sistema cria um `RecurringSchedule` no módulo financeiro.
    *   Cron: `0 0 5 * *` (Todo dia 5).
    *   Template: Valor do condomínio, Descrição "Condomínio Mês X".
3.  **Fluxo Mensal:**
    *   O Scheduler do módulo financeiro roda dia 5 e cria um `Receivable` (Conta a Receber).
    *   O módulo financeiro emite `ReceivableCreated`.
4.  **Reação do Domínio:**
    *   Listener `GenerateBankSlip` (no módulo de Condomínio) escuta `ReceivableCreated`.
    *   Listener gera o PDF do boleto bancário e envia por e-mail para o morador.
    *   Quando o morador paga o boleto, o banco notifica via Webhook.
    *   O módulo financeiro processa o Webhook e marca o `Receivable` como `Paid`.

### Benefícios da Abordagem
*   **Desacoplamento:** O módulo financeiro não sabe o que é um "Curso" ou um "Apartamento".
*   **Evolução Independente:** Podemos trocar o gateway de pagamento (Stripe para Pagar.me) sem alterar uma linha de código no módulo de Cursos ou Condomínio.
*   **Manutenibilidade:** Regras financeiras complexas (arredondamento, conversão de moeda) ficam isoladas e testadas unitariamente dentro do pacote.

---

## 9. Guia de Manutenção e Evolução

Este guia define as regras de governança para garantir a longevidade e a qualidade do pacote.

### Boas Práticas Obrigatórias
1.  **Testes de Regressão:** Nenhuma PR é aceita sem testes que cubram o novo cenário e garantam que o antigo continua funcionando.
2.  **Imutabilidade de Contratos:** Interfaces públicas (`Contracts`) não devem ser alteradas levianamente. Se precisar mudar, crie uma nova versão (`V2`) e marque a antiga como `@deprecated`.
3.  **Documentação Viva:** Toda nova funcionalidade deve ser documentada neste arquivo ou na Wiki do repositório.
4.  **Code Review Rigoroso:** Focar em design e arquitetura, não apenas em sintaxe. Perguntar: "Isso acopla o pacote a um negócio específico?".

### Limites Claros (O que NUNCA entra)
*   **Regras de Impostos Específicos:** Se o Brasil mudar a regra do ICMS, este pacote não deve precisar de update. Use um pacote fiscal dedicado.
*   **Lógica de UI:** O pacote não deve conter Views Blade, CSS ou JS, exceto se for um painel administrativo opcional e isolado.
*   **Dependências Pesadas:** Evitar adicionar dependências no `composer.json` que não sejam estritamente necessárias.

### Anti-Padrões a Evitar
*   **God Object:** Evitar que a classe `Wallet` ou `TransactionService` faça tudo. Quebre em serviços menores (`TransferService`, `DepositService`).
*   **Leaky Abstractions:** Não exponha exceções do Guzzle ou do Stripe diretamente. Capture e lance `PaymentGatewayException`.
*   **Magic Strings:** Use Enums ou Constantes para tudo (Status, Tipos).

### Diretrizes para Novas Funcionalidades
*   **Pergunta de Ouro:** "Essa funcionalidade é útil para uma Academia E para um Banco?"
    *   Sim -> Pode entrar no Core.
    *   Não -> Deve ser implementada no sistema consumidor ou como um plugin/extensão.

### Estratégia de Testes
*   **Unitários:** Testar a lógica matemática e de estado das Entidades (sem banco).
*   **Integração:** Testar Repositórios e Services com banco de dados em memória (SQLite) ou Docker.
*   **Contrato:** Garantir que os Gateways Fake se comportam igual aos reais.

### Versionamento Semântico (SemVer)
*   **Major (1.0.0 -> 2.0.0):** Quebra de compatibilidade (mudança em Interfaces, remoção de métodos públicos).
*   **Minor (1.1.0 -> 1.2.0):** Nova funcionalidade compatível (adicionar método opcional, novo driver).
*   **Patch (1.1.1 -> 1.1.2):** Correção de bugs internos.

### Sinais de Alerta (Code Smells Arquiteturais)
*   Se você precisar importar um Model `User` ou `Order` dentro do pacote -> **PARE**.
*   Se você precisar adicionar um `if ($type == 'hotel')` -> **PARE**.
*   Se os testes precisarem de muitos mocks para rodar -> O acoplamento está alto.

### Recomendações Finais
*   Mantenha o pacote pequeno e focado.
*   Prefira composição sobre herança.
*   Respeite o princípio Open/Closed (Aberto para extensão, fechado para modificação).
