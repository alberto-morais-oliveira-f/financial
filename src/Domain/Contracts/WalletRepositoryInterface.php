<?php

namespace Am2tec\Financial\Domain\Contracts;

use Am2tec\Financial\Domain\Entities\Wallet;

interface WalletRepositoryInterface
{
    public function save(Wallet $wallet): Wallet;
    public function findById(string $uuid): ?Wallet;
    public function findByOwner(AccountOwner $owner): array;
}
