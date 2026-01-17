<?php

namespace Am2tec\Financial\Domain\Entities;

use DateTimeImmutable;
use Am2tec\Financial\Domain\Enums\ScheduleStatus;
use Am2tec\Financial\Domain\ValueObjects\Money;

class RecurringSchedule
{
    public function __construct(
        public readonly ?string $uuid,
        public readonly string $walletId,
        public readonly Money $amount,
        public readonly string $cronExpression, // ex: '0 0 1 * *'
        public readonly string $description,
        public DateTimeImmutable $nextRunAt,
        public ScheduleStatus $status = ScheduleStatus::ACTIVE,
        public readonly ?DateTimeImmutable $endsAt = null,
        public readonly array $metadata = []
    ) {}

    public function shouldRunNow(): bool
    {
        return $this->status === ScheduleStatus::ACTIVE && $this->nextRunAt <= new DateTimeImmutable();
    }

    public function updateNextRunDate(DateTimeImmutable $nextDate): void
    {
        $this->nextRunAt = $nextDate;
    }
}
