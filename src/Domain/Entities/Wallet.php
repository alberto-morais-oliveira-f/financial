<?php

namespace Am2tec\Financial\Domain\Entities;

use Am2tec\Financial\Domain\Contracts\AccountOwner;
use Am2tec\Financial\Domain\Enums\WalletStatus;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;

class Wallet
{
    public function __construct(
        public readonly ?string $uuid,
        public readonly AccountOwner $owner,
        public readonly Currency $currency,
        public Money $balance,
        public WalletStatus $status = WalletStatus::ACTIVE,
        public readonly ?string $name = null,
        public readonly ?string $description = null,
    ) {}

    public function deposit(Money $amount): void
    {
        $this->balance = $this->balance->add($amount);
    }

    public function withdraw(Money $amount): void
    {
        $this->balance = $this->balance->subtract($amount);
    }

    public function freeze(): void
    {
        $this->status = WalletStatus::FROZEN;
    }

    public function isActive(): bool
    {
        return $this->status === WalletStatus::ACTIVE;
    }
}
