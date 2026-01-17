<?php

namespace Am2tec\Financial\Domain\Services;

use Am2tec\Financial\Application\DTOs\DreData;
use Am2tec\Financial\Domain\Contracts\DreRepositoryInterface;
use Illuminate\Support\Collection;

class DreService
{
    public function __construct(protected DreRepositoryInterface $dreRepository)
    {
    }

    /**
     * Gera a Demonstração do Resultado do Exercício (DRE) para um dado período.
     *
     * @param string $startDate
     * @param string $endDate
     * @return array{summary: array<string, float>, detailed: Collection<int, DreData>}
     */
    public function generate(string $startDate, string $endDate): array
    {
        $dreRawData = $this->dreRepository->getDreData($startDate, $endDate);

        $detailed = DreData::collection($dreRawData);

        $groupedByType = $detailed->groupBy('category_type');

        $totals = $groupedByType->map(fn (Collection $group) => $group->sum('total_amount'));

        $revenue = (float) $totals->get('REVENUE', 0);
        $costs = (float) $totals->get('COST', 0);
        $expenses = (float) $totals->get('EXPENSE', 0);
        $taxes = (float) $totals->get('TAX', 0);

        $grossProfit = $revenue - $costs;
        $operatingProfit = $grossProfit - $expenses - $taxes;

        return [
            'summary' => [
                'revenue' => $revenue,
                'costs' => $costs,
                'gross_profit' => $grossProfit,
                'expenses' => $expenses,
                'taxes' => $taxes,
                'operating_profit' => $operatingProfit,
            ],
            'detailed' => $detailed,
        ];
    }
}
