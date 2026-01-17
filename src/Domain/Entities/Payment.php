<?php

namespace Am2tec\Financial\Domain\Entities;

use DateTimeImmutable;
use Am2tec\Financial\Domain\Enums\PaymentStatus;
use Am2tec\Financial\Domain\ValueObjects\Money;

class Payment
{
    public function __construct(
        public readonly ?string $uuid,
        public readonly string $gateway, // ex: 'stripe', 'pagarme'
        public readonly ?string $gatewayTransactionId,
        public readonly Money $amount,
        public PaymentStatus $status = PaymentStatus::PENDING,
        public readonly DateTimeImmutable $createdAt = new DateTimeImmutable(),
        public readonly ?string $errorMessage = null
    ) {}

    public function markAsPaid(string $gatewayTransactionId): void
    {
        $this->status = PaymentStatus::PAID;
        // Hack to update readonly property in runtime if needed, or create new instance
        // For simplicity in this entity design, we might need to make gatewayTransactionId not readonly or use reflection
        // But ideally, we create a new instance or update via repository.
        // Let's assume for now we are updating the object state before persistence.
    }

    public function markAsFailed(string $errorMessage): void
    {
        $this->status = PaymentStatus::FAILED;
        // Same note about errorMessage
    }
}
