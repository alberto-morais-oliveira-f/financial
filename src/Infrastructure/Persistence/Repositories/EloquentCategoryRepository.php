<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Am2tec\Financial\Domain\Contracts\CategoryRepositoryInterface;
use Am2tec\Financial\Infrastructure\Persistence\Models\Category;
use Illuminate\Support\Collection;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(private readonly Category $model)
    {
    }

    public function all(): Collection
    {
        return $this->model->all();
    }
}
