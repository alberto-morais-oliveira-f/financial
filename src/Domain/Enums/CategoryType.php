<?php

declare(strict_types=1);

namespace Am2tec\Financial\Domain\Enums;

/**
 * Enum que define os tipos contábeis fundamentais de uma Categoria Financeira.
 *
 * Estes tipos são a base para a geração de relatórios financeiros como DRE e DFC.
 * Eles são agnósticos ao domínio de negócio e representam conceitos contábeis universais.
 */
enum CategoryType: string
{
    /**
     * RECEITA: Entradas de dinheiro provenientes da atividade principal da empresa.
     * Impacto no DRE: Aumenta a Receita Bruta.
     */
    case REVENUE = 'revenue';

    /**
     * DESPESA: Gastos para manter a operação, não diretamente ligados ao produto/serviço.
     * Ex: Aluguel de escritório, salários administrativos.
     * Impacto no DRE: Aumenta as Despesas Operacionais.
     */
    case EXPENSE = 'expense';

    /**
     * CUSTO: Gastos diretamente associados à produção ou prestação do serviço.
     * Ex: Matéria-prima, custo da mercadoria vendida (CMV).
     * Impacto no DRE: Aumenta os Custos (COGS/COS).
     */
    case COST = 'cost';

    /**
     * IMPOSTO: Impostos pagos ou retidos sobre as operações.
     * Impacto no DRE: Deduzido da receita ou somado às despesas.
     */
    case TAX = 'tax';

    /**
     * ATIVO: Bens e direitos da empresa.
     * Ex: Caixa, Contas a Receber, Equipamentos.
     * Impacto no Balanço Patrimonial.
     */
    case ASSET = 'asset';

    /**
     * PASSIVO: Obrigações da empresa com terceiros.
     * Ex: Empréstimos, Contas a Pagar.
     * Impacto no Balanço Patrimonial.
     */
    case LIABILITY = 'liability';

    /**
     * PATRIMÔNIO LÍQUIDO: Capital próprio da empresa.
     * Ex: Capital Social, Lucros Acumulados.
     * Impacto no Balanço Patrimonial.
     */
    case EQUITY = 'equity';
}
