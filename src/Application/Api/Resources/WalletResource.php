<?php

namespace Am2tec\Financial\Application\Api\Resources;

use Spatie\LaravelData\Data;
use Am2tec\Financial\Domain\Entities\Wallet;

class WalletResource extends Data
{
    public function __construct(
        public string $id,
        public string $owner_id,
        public string $owner_type,
        public string $name,
        public int $balance,
        public string $currency,
        public string $status, // CORREÇÃO: Restaurado
    ) {}

    public static function fromEntity(Wallet $wallet): self
    {
        return new self(
            id: $wallet->uuid,
            owner_id: $wallet->owner->getOwnerId(),
            owner_type: $wallet->owner->getOwnerType(),
            name: $wallet->name,
            balance: $wallet->balance->amount,
            currency: $wallet->balance->currency->code,
            status: $wallet->status->value // CORREÇÃO: Restaurado
        );
    }
}
