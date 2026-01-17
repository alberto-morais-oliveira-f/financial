<?php

namespace Am2tec\Financial\Domain\Contracts;

use Am2tec\Financial\Domain\Entities\Wallet;
use Am2tec\Financial\Domain\ValueObjects\Owner; // CORREÇÃO

interface WalletRepositoryInterface
{
    public function save(Wallet $wallet): Wallet;

    public function findById(string $uuid): ?Wallet;

    public function findByOwner(Owner $owner): array; // CORREÇÃO
}
