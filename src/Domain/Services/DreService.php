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

    public function generate(string $startDate, string $endDate): array
    {
        $dreRawData = $this->dreRepository->getDreData($startDate, $endDate);

        // Agora DreData é uma classe PHP simples, usamos o método from() que criamos.
        $detailedDtos = $dreRawData->map(function ($row) {
            return DreData::from($row);
        });

        $groupedByType = $detailedDtos->groupBy('category_type');

        $totals = $groupedByType->map(fn (Collection $group) => $group->sum('total_amount'));

        $revenue = (float) $totals->get('revenue', 0);
        $costs = (float) $totals->get('cost', 0);
        $expenses = (float) $totals->get('expense', 0);
        $taxes = (float) $totals->get('tax', 0);

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
            // Retornar a coleção de objetos DreData simples, que o Laravel serializará para JSON.
            'detailed' => $detailedDtos->toArray(),
        ];
    }
}
