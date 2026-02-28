<?php

namespace Am2tec\Financial\Domain\Entities;

use DateTimeImmutable;
use Am2tec\Financial\Domain\Enums\TitleStatus;
use Am2tec\Financial\Domain\Enums\TitleType;
use Am2tec\Financial\Domain\ValueObjects\Money;

class Title
{
    public function __construct(
        public readonly ?string $uuid,
        public readonly string $walletId,
        public readonly ?string $supplierUuid,
        public readonly TitleType $type,
        public readonly Money $amount,
        public readonly DateTimeImmutable $dueDate,
        public readonly string $description,
        public TitleStatus $status = TitleStatus::PENDING,
        public readonly DateTimeImmutable $createdAt = new DateTimeImmutable(),
        public readonly array $metadata = []
    ) {}

    public function markAsPaid(): void
    {
        $this->status = TitleStatus::PAID;
    }

    public function markAsCancelled(): void
    {
        $this->status = TitleStatus::CANCELLED;
    }

    public function isOverdue(): bool
    {
        return $this->status === TitleStatus::PENDING && $this->dueDate < new DateTimeImmutable();
    }
}
