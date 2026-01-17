<?php

namespace Am2tec\Financial\Application\Api\Resources;

use Spatie\LaravelData\Data;
use Am2tec\Financial\Domain\Entities\Transaction;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

class TransactionResource extends Data
{
    public function __construct(
        public string $id,
        public string $description,
        public string $status,
        public string $created_at,
        #[DataCollectionOf(EntryResource::class)]
        public DataCollection $entries,
    ) {}

    public static function fromEntity(Transaction $transaction): self
    {
        return new self(
            id: $transaction->uuid,
            description: $transaction->description,
            status: $transaction->status->value,
            created_at: $transaction->createdAt->format('Y-m-d H:i:s'),
            entries: EntryResource::collection($transaction->getEntries())
        );
    }
}
