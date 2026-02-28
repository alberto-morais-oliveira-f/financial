<?php

namespace Am2tec\Financial\Domain\Contracts;

interface SupplierRepositoryInterface
{
    public function all();
    public function findOrFail(string $uuid);
    public function create(array $data);
    public function update(string $uuid, array $data);
    public function delete(string $uuid);
}
