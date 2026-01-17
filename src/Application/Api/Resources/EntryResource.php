<?php

namespace Am2tec\Financial\Application\Api\Resources;

use Spatie\LaravelData\Data;
use Am2tec\Financial\Domain\Entities\Entry;

class EntryResource extends Data
{
    public function __construct(
        public string $wallet_id,
        public string $type,
        public int $amount,
    ) {}

    public static function fromEntity(Entry $entry): self
    {
        return new self(
            wallet_id: $entry->walletId,
            type: $entry->type->value,
            amount: $entry->amount->amount,
        );
    }
}
