<?php

namespace Am2tec\Financial\Domain\Contracts;

use Illuminate\Support\Collection;

interface DreRepositoryInterface
{
    /**
     * Obtém os dados para a Demonstração do Resultado do Exercício (DRE).
     *
     * @param string $startDate
     * @param string $endDate
     * @return Collection
     */
    public function getDreData(string $startDate, string $endDate): Collection;
}
