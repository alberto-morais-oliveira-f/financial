<?php

namespace Am2tec\Financial\Domain\Contracts;

use Am2tec\Financial\Domain\Entities\Transaction;

interface TransactionRepositoryInterface
{
    public function save(Transaction $transaction): Transaction;
    public function findById(string $uuid): ?Transaction;
}
