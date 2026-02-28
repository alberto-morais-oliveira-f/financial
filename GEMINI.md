# Diretrizes para Agentes de IA - Módulo Financial

Este documento contém instruções fundamentais e mandatos arquiteturais para agentes de IA que operam neste repositório. **Siga estas regras rigorosamente.**

## 1. Contexto do Projeto
- **Natureza:** Pacote Laravel para gestão financeira (Livro Razão/General Ledger).
- **Stack:** PHP 8.4+, Laravel 12.x, PostgreSQL.
- **Arquitetura:** DDD / Hexagonal.
- **Conceito Core:** Double-Entry Bookkeeping (Toda transação deve ter Débitos e Créditos que se anulam).

## 2. Estrutura de Diretórios e Responsabilidades

### `src/Domain/` (O Núcleo)
- **Entities:** Lógica de negócio e estado (ex: `Wallet`, `Transaction`). Evite dependências do Laravel aqui.
- **ValueObjects:** Objetos imutáveis (ex: `Money`, `Currency`).
- **Contracts:** Interfaces para Repositórios e Adaptores.
- **Services:** Lógica de domínio que envolve múltiplas entidades.
- **Events:** Eventos de domínio (ex: `TransactionPosted`).

### `src/Application/` (Orquestração)
- **DTOs:** Utilize `Spatie\LaravelData` ou classes simples para transferência de dados. **Nunca** passe Models do Eloquent diretamente para os Services.
- **UseCases / Actions:** Orquestram a lógica entre o domínio e a infraestrutura.

### `src/Infrastructure/` (Implementação Técnica)
- **Persistence/Models:** Models do Eloquent (mapeamento de banco). Devem ser mantidos "burros".
- **Persistence/Repositories:** Implementação concreta dos `Contracts` do domínio usando Eloquent.
- **Http/Controllers:** Apenas recebem requisições, validam (FormRequests) e chamam a camada de Aplicação.
- **Providers:** Configuração do Service Container e registro do pacote.

## 3. Padrões de Codificação (Mandatos)

### Backend (PHP 8.4)
- **Tipagem Estrita:** Sempre declare tipos de argumentos e retorno. Use `readonly` para DTOs e Value Objects.
- **Nomes em Inglês:** Classes, métodos e variáveis **devem** ser em inglês.
- **Traduções:** Textos de interface **devem** usar `__('financial::messages.key')`. Arquivos em `resources/lang/`.
- **Performance:** Evite queries N+1. Use Eager Loading (`with()`) conscientemente.
- **Imutabilidade:** Transações confirmadas (`POSTED`) nunca devem ser editadas ou deletadas. Use estornos (Refunds).

### Frontend (Blade/Bootstrap/Vite)
- **Bootstrap 5:** Siga rigorosamente os componentes do Bootstrap.
- **Sem Scripts Inline:** Use `@push('scripts')` ou arquivos `.js` em `resources/js/`.
- **DataTables:** Use o padrão do pacote para tabelas (Yajra DataTables).
- **Vite:** Assets são gerenciados via Vite.

## 4. Fluxo de Trabalho de Desenvolvimento

1. **Novas Features:**
   - Comece pelo `Domain` (Entidade/Contrato).
   - Implemente a `Infrastructure` (Model/Migration/Repository).
   - Crie o `UseCase` na camada de `Application`.
   - Por fim, o `Controller` e a `View`.

2. **Testes:**
   - **Unitários:** Para lógica de `Domain` e `ValueObjects`.
   - **Feature:** Para `Endpoints API` e `Fluxos Completos` (Service -> Repository -> DB).
   - Localização: `tests/Unit` e `tests/Feature`.

3. **Banco de Dados:**
   - Migrations devem usar o prefixo de tabela definido no config (se aplicável).
   - Use `uuid` como chave primária para todas as entidades principais.

## 5. Integrações Externas
- **Gateways:** Sempre através de `PaymentGatewayAdapter`. Nunca acople lógica de Stripe/MercadoPago diretamente nos Services.
- **Polimorfismo:** Entidades donas de carteiras devem implementar `AccountOwner`.

## 6. O que NÃO fazer
- **NÃO** coloque lógica de negócio em Controllers ou Models do Eloquent.
- **NÃO** use `float` para valores monetários. Use `Money` (inteiros em centavos).
- **NÃO** altere transações existentes. Sempre crie uma nova transação de correção/estorno.
- **NÃO** adicione dependências pesadas ao `composer.json` sem necessidade extrema.
