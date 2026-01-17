<?php

namespace Am2tec\Financial\Domain\Entities;

use DateTimeImmutable;
use Am2tec\Financial\Domain\Enums\RefundStatus;
use Am2tec\Financial\Domain\ValueObjects\Money;

class Refund
{
    public function __construct(
        public readonly ?string $uuid,
        public readonly string $paymentId,
        public readonly Money $amount,
        public RefundStatus $status = RefundStatus::PENDING,
        public readonly ?string $gatewayRefundId = null,
        public readonly ?string $reason = null,
        public readonly ?string $failureReason = null,
        public readonly DateTimeImmutable $createdAt = new DateTimeImmutable(),
    ) {}

    public function markAsProcessed(string $gatewayRefundId): void
    {
        $this->status = RefundStatus::PROCESSED;
        // Ideally, create a new instance as gatewayRefundId is readonly
    }

    public function markAsFailed(string $reason): void
    {
        $this->status = RefundStatus::FAILED;
        // Ideally, create a new instance as failureReason is readonly
    }
}
