# Estrutura de Categorias Financeiras (Plano de Contas Simplificado)

## 1. Conceito de Categoria Financeira

A **Categoria Financeira** é um artefato de classificação, análogo a uma conta em um Plano de Contas contábil. Sua única responsabilidade é "etiquetar" ou "marcar" um lançamento financeiro (`Entry`) para indicar sua natureza contábil.

- **Propósito**: Agrupar e agregar lançamentos para a geração de relatórios financeiros (DRE, DFC, balancetes, etc.).
- **Agnosticismo**: A categoria não conhece o domínio. Ela não sabe o que é uma "venda de sapato" ou uma "mensalidade de academia". Ela conhece apenas conceitos contábeis como "Receita" (`REVENUE`) ou "Despesa" (`EXPENSE`). O domínio externo é quem decide qual categoria aplicar a um determinado evento de negócio.
- **Não é uma Carteira (`Wallet`)**: Uma `Wallet` representa **onde** o dinheiro está (ex: "Caixa", "Conta Bradesco"). Uma `Category` representa **o porquê** do dinheiro ter se movimentado (ex: "Receita de Serviços", "Despesa com Aluguel").

## 2. Modelagem da Entidade `Category`

A entidade `Category` será representada pela tabela `financial_categories`.

**Atributos da Tabela `financial_categories`:**

- `uuid` (Primary Key): Identificador único universal.
- `parent_uuid` (UUID, Nullable, FK para `financial_categories.uuid`): Para criar a estrutura de hierarquia (árvore).
- `name` (String): Nome legível da categoria (ex: "Despesas com Pessoal").
- `slug` (String, Unique): Versão "amigável para URL" do nome (ex: "despesas-com-pessoal").
- `type` (Enum): O tipo contábil fundamental da categoria. Este é o atributo mais crítico para a geração de relatórios.
- `is_system_category` (Boolean, Default: `false`): Indica se é uma categoria padrão do sistema, protegendo-a contra exclusão.
- `description` (Text, Nullable): Um campo para detalhar o propósito da categoria.
- `created_at`, `updated_at`: Timestamps padrão.

**Responsabilidades da Entidade:**
- Apenas manter seus próprios dados.
- Definir a relação de hierarquia (ex: `parent()` e `children()` no modelo Eloquent).

## 3. Tipos de Categorias (`type`)

Os tipos são a fundação dos relatórios financeiros. Eles são fixos e representam os grandes grupos contábeis.

- `REVENUE` (Receita): Entradas de dinheiro provenientes da atividade principal da empresa.
- `EXPENSE` (Despesa): Gastos necessários para manter a operação da empresa, mas não diretamente ligados ao produto/serviço.
- `COST` (Custo): Gastos diretamente associados à produção do bem ou à prestação do serviço (COGS/COS).
- `ASSET` (Ativo): Bens e direitos da empresa (ex: caixa, contas a receber, equipamento).
- `LIABILITY` (Passivo): Obrigações da empresa com terceiros (ex: empréstimos, contas a pagar).
- `EQUITY` (Patrimônio Líquido): O capital próprio da empresa (capital social, lucros acumulados).
- `TAX` (Imposto): Impostos pagos ou retidos.

## 4. Estratégia de Hierarquia

A hierarquia será implementada usando o campo `parent_uuid`. Isso permite a criação de um plano de contas com múltiplos níveis de detalhe, essencial para relatórios de "drill-down".

**Exemplo de Hierarquia:**

- `EXPENSE` (Tipo Raiz)
  - `Despesas Operacionais` (Nível 1)
    - `Despesas com Pessoal` (Nível 2)
      - `Salários` (Nível 3)
      - `Benefícios` (Nível 3)
    - `Despesas Administrativas` (Nível 2)
      - `Aluguel de Escritório` (Nível 3)

## 5. Associação da Categoria

A categoria **deve** ser associada à entidade `Entry`.

- **Por quê?**: A `Transaction` é o evento completo, mas a `Entry` é a perna individual do lançamento (o débito ou o crédito). Cada perna pode ter uma natureza contábil diferente.
- **Implementação**: Adicionar uma coluna `category_uuid` (UUID, Nullable) à tabela `financial_entries`.
- **Exemplo de Transação de Venda:**
  - `Transaction`: "Venda do Produto Y"
    - `Entry 1 (Débito)`: R$ 100,00 na `Wallet` "Contas a Receber". `category_uuid` aponta para uma categoria do tipo `ASSET`.
    - `Entry 2 (Crédito)`: R$ 100,00. `category_uuid` aponta para uma categoria do tipo `REVENUE` (ex: "Receita de Vendas").

## 6. Regras de Imutabilidade

A imutabilidade é crucial para a integridade dos relatórios.

- **Regra**: Uma vez que uma `Entry` tenha seu status alterado para `POSTED`, sua `category_uuid` **NÃO PODE SER ALTERADA**.
- **Justificativa**: Alterar a categoria de um lançamento passado invalidaria todos os relatórios financeiros já gerados (DRE, DFC) para aquele período, quebrando o princípio da consistência contábil.
- **Correção**: Se um lançamento foi categorizado incorretamente, a única forma de corrigi-lo é através de uma **transação de reclassificação**. Isso envolve criar uma nova transação que debita a categoria correta e credita a categoria incorreta (ou vice-versa), mantendo um rastro de auditoria claro.

## 7. Estratégia para Categorias Padrão (Seed)

O pacote deve fornecer um conjunto mínimo de categorias para que o sistema seja funcional "out-of-the-box".

- **Implementação**: Criar um `Seeder` (`CategorySeeder`).
- **Ação do Seeder**: Popular a tabela `financial_categories` com as categorias raiz, uma para cada `type` (`REVENUE`, `EXPENSE`, etc.). Essas categorias devem ser marcadas com `is_system_category = true`.
- **Comando**: Fornecer um comando Artisan para executar o seeder, como `php artisan financial:seed-categories`. Isso permite que o usuário do pacote inicialize seu plano de contas.

## 8. Impacto nos Relatórios DRE e DFC

A estrutura de categorias é o que **habilita** a geração de relatórios significativos.

- **DRE (Demonstração do Resultado do Exercício)**:
  - **Receita Bruta**: `SUM(valor)` de todas as `Entries` onde `Category.type = 'REVENUE'`.
  - **Custos**: `SUM(valor)` de todas as `Entries` onde `Category.type = 'COST'`.
  - **Lucro Bruto**: `Receita Bruta - Custos`.
  - **Despesas Operacionais**: `SUM(valor)` de todas as `Entries` onde `Category.type = 'EXPENSE'`.
  - **Lucro Operacional**: `Lucro Bruto - Despesas`.

- **DFC (Demonstração do Fluxo de Caixa)**:
  - O DFC analisa o movimento em `Wallets` do tipo "Caixa" ou "Banco". A categoria da `Entry` classifica a **natureza** desse fluxo.
  - **Fluxo de Caixa Operacional**: Movimentações de caixa cujas contrapartidas são categorias de `REVENUE`, `EXPENSE`, `COST`, `TAX`.
  - **Fluxo de Caixa de Investimento**: Movimentações de caixa para compra/venda de `ASSET`.
  - **Fluxo de Caixa de Financiamento**: Movimentações de caixa de/para `LIABILITY` (empréstimos) ou `EQUITY` (aportes).

## 9. Anti-Padrões a Evitar

- **Categorias de Negócio**: Evitar criar categorias como `Venda do Produto XPTO` ou `Plano Premium`. A categoria correta seria `Receita de Vendas` ou `Receita de Assinaturas`. O detalhe do produto pertence ao `metadata` da transação.
- **Categorias como Contas/Pessoas**: Não criar categorias como `Stripe` ou `Fornecedor Acme`. `Stripe` é uma `Wallet`. `Fornecedor Acme` é um `AccountOwner` externo ao módulo.
- **Lógica de Negócio na Categoria**: A categoria não deve ter métodos como `calcularImposto()`. Ela é um DTO (Data Transfer Object) glorificado. A lógica pertence aos `Services`.
- **Alterar Lançamentos Postados**: Reafirmando: nunca permitir a alteração da categoria de uma `Entry` que já foi confirmada.

## 10. Recomendações para Evolução Futura

- **Orçamentos (Budgeting)**: Com as categorias definidas, o próximo passo lógico é um módulo de orçamento, onde se pode definir um valor orçado por categoria para um determinado período (`budgets(category_uuid, period, amount)`).
- **Associação em Títulos (`Titles`)**: Estender a associação de `category_uuid` para a entidade `Title` (Contas a Pagar/Receber). Isso permite a **previsão de fluxo de caixa** e a geração de um DRE por regime de competência futuro, analisando os títulos em aberto.
- **Relatórios Customizáveis**: Criar uma interface onde os usuários possam montar seus próprios relatórios, selecionando, agrupando e totalizando por categorias.
- **Metadados**: Adicionar um campo `metadata` (JSON) na entidade `Category` para permitir que os usuários do pacote adicionem informações específicas de seu domínio sem a necessidade de alterar o schema do banco de dados.

## 11. Plano de Implementação Técnica

Esta seção detalha os artefatos de código que serão criados para implementar a funcionalidade de categorias.

1.  **Enum `CategoryType`**:
    -   **Arquivo**: `src/Domain/Enums/CategoryType.php`
    -   **Propósito**: Definir os tipos contábeis (`REVENUE`, `EXPENSE`, `COST`, etc.) de forma segura e centralizada.

2.  **Migration para `financial_categories`**:
    -   **Arquivo**: `src/Infrastructure/Persistence/Migrations/YYYY_MM_DD_HHMMSS_create_financial_categories_table.php`
    -   **Propósito**: Criar a tabela para armazenar as categorias financeiras.
    -   **Campos Notáveis**: `uuid`, `parent_uuid`, `name`, `code`, `type`, `is_active`.

3.  **Migration para `financial_entries`**:
    -   **Arquivo**: `src/Infrastructure/Persistence/Migrations/YYYY_MM_DD_HHMMSS_add_category_uuid_to_financial_entries_table.php`
    -   **Propósito**: Adicionar a chave estrangeira `category_uuid` na tabela de lançamentos (`entries`).
    -   **Detalhe**: O campo será `nullable` e terá uma restrição de chave estrangeira para a tabela `financial_categories`.

4.  **Model `Category`**:
    -   **Arquivo**: `src/Infrastructure/Persistence/Models/Category.php`
    -   **Propósito**: Implementação Eloquent da entidade Categoria.
    -   **Detalhes**: Incluirá os casts para o enum `CategoryType`, relacionamentos `parent()` e `children()` (self-referencing), e o relacionamento `entries()`.

5.  **Atualização do Model `Entry`**:
    -   **Arquivo**: `src/Infrastructure/Persistence/Models/Entry.php`
    -   **Propósito**: Adicionar o relacionamento `category()` (`belongsTo`).
    -   **Observação**: A lógica para impedir a alteração da categoria em um lançamento `POSTED` será implementada no `TransactionService`, não no model, para manter o model "anêmico" e a lógica de negócio nos serviços.

6.  **Seeder de Categorias**:
    -   **Arquivo**: `src/Infrastructure/Persistence/Seeders/CategorySeeder.php`
    -   **Propósito**: Popular a tabela `financial_categories` com um conjunto mínimo de categorias padrão (Receitas, Despesas, Custos) para que o sistema seja utilizável desde o início.
