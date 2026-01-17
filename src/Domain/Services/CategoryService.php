<?php

namespace Am2tec\Financial\Domain\Services;

use Am2tec\Financial\Domain\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Collection;

class CategoryService
{
    public function __construct(private readonly CategoryRepositoryInterface $repository)
    {
    }

    public function all(): Collection
    {
        return $this->repository->all();
    }
}
