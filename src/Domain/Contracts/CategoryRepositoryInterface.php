<?php

namespace Am2tec\Financial\Domain\Contracts;

use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    public function all(): Collection;
}
