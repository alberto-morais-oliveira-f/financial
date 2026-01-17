<?php

namespace Am2tec\Financial\Domain\Entities;

use DateTimeImmutable;
use Am2tec\Financial\Domain\Enums\TransactionStatus;

class Transaction
{
    /** @var Entry[] */
    private array $entries = [];

    public function __construct(
        public readonly ?string $uuid,
        public readonly string $referenceCode,
        public readonly string $description,
        public TransactionStatus $status = TransactionStatus::PENDING,
        public readonly DateTimeImmutable $createdAt = new DateTimeImmutable(),
        public readonly array $metadata = []
    ) {}

    public function addEntry(Entry $entry): void
    {
        $this->entries[] = $entry;
    }

    /** @return Entry[] */
    public function getEntries(): array
    {
        return $this->entries;
    }

    public function isBalanced(): bool
    {
        $debits = 0;
        $credits = 0;

        foreach ($this->entries as $entry) {
            if ($entry->isDebit()) {
                $debits += $entry->amount->amount;
            } else {
                $credits += $entry->amount->amount;
            }
        }

        return $debits === $credits;
    }

    public function markAsPosted(): void
    {
        if (!$this->isBalanced()) {
            throw new \DomainException("Transaction is not balanced.");
        }
        $this->status = TransactionStatus::POSTED;
    }
}
