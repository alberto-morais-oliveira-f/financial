<?php

namespace Am2tec\Financial\Domain\Entities;

use Am2tec\Financial\Domain\Enums\WalletStatus;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Domain\ValueObjects\Owner;

class Wallet
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $name,
        public readonly Money $balance,
        public readonly Owner $owner,
        public readonly WalletStatus $status,
    ) {}

    public function withdraw(Money $amount): self
    {
        return new self(
            uuid: $this->uuid,
            name: $this->name,
            balance: $this->balance->subtract($amount),
            owner: $this->owner,
            status: $this->status
        );
    }

    public function deposit(Money $amount): self
    {
        return new self(
            uuid: $this->uuid,
            name: $this->name,
            balance: $this->balance->add($amount),
            owner: $this->owner,
            status: $this->status
        );
    }
}
