<?php

namespace Am2tec\Financial\Application\Api\Resources;

use Spatie\LaravelData\Data;
use Am2tec\Financial\Domain\Entities\Transaction;

class TransactionResource extends Data
{
    public function __construct(
        public string $id,
        public string $status,
        public ?string $description,
        /** @var \Spatie\LaravelData\DataCollection<int, \Am2tec\Financial\Application\Api\Resources\EntryResource> */
        public \Spatie\LaravelData\DataCollection $entries,
    ) {}

    public static function fromEntity(Transaction $transaction): self
    {
        return new self(
            id: $transaction->getId(),
            status: $transaction->getStatus()->value,
            description: $transaction->getDescription(),
            // CORREÇÃO: Usar o método estático `collect`
            entries: EntryResource::collect($transaction->getEntries())
        );
    }
}
