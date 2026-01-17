<?php

namespace Am2tec\Financial\Application\Api\Resources;

use Spatie\LaravelData\Data;
use Am2tec\Financial\Domain\Entities\Transaction;
use Illuminate\Support\Collection;

class TransactionResource extends Data
{
    public function __construct(
        public string $id,
        public string $status,
        public ?string $description,
        /** @var Collection<int, EntryResource> */
        public Collection $entries,
    ) {}

    public static function fromEntity(Transaction $transaction): self
    {
        // CORREÇÃO: Mapear manualmente as entries para EntryResource
        $entryResources = collect($transaction->getEntries())->map(function ($entry) {
            return EntryResource::fromEntity($entry);
        });

        return new self(
            id: $transaction->uuid,
            status: $transaction->status->value,
            description: $transaction->description,
            entries: $entryResources
        );
    }
}
