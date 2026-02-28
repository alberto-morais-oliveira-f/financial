<?php

namespace Am2tec\Financial\Domain\Entities;

use Am2tec\Financial\Domain\Enums\EntryType;
use Am2tec\Financial\Domain\ValueObjects\Money;

class Entry
{
    public function __construct(
        public readonly ?string $uuid,
        public readonly string $walletId,
        public readonly ?string $categoryUuid,
        public readonly ?string $supplierUuid,
        public readonly EntryType $type,
        public readonly Money $amount,
        public readonly ?Money $beforeBalance = null,
        public readonly ?Money $afterBalance = null
    ) {}

    public function isDebit(): bool
    {
        return $this->type === EntryType::DEBIT;
    }

    public function isCredit(): bool
    {
        return $this->type === EntryType::CREDIT;
    }
}
