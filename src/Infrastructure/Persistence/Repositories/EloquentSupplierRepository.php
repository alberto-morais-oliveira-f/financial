<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Am2tec\Financial\Domain\Contracts\SupplierRepositoryInterface;
use Am2tec\Financial\Infrastructure\Persistence\Models\Supplier;

class EloquentSupplierRepository implements SupplierRepositoryInterface
{
    public function all()
    {
        return Supplier::all();
    }

    public function findOrFail(string $uuid)
    {
        return Supplier::findOrFail($uuid);
    }

    public function create(array $data)
    {
        return Supplier::create($data);
    }

    public function update(string $uuid, array $data)
    {
        $supplier = $this->findOrFail($uuid);
        $supplier->update($data);
        return $supplier;
    }

    public function delete(string $uuid)
    {
        $supplier = $this->findOrFail($uuid);
        return $supplier->delete();
    }
}
