<?php

namespace Am2tec\Financial\Application\Api\Resources;

use Spatie\LaravelData\Data;
use Am2tec\Financial\Domain\Entities\Refund;

class RefundResource extends Data
{
    public function __construct(
        public string $id,
        public string $payment_id,
        public int $amount,
        public string $currency,
        public string $status,
        public ?string $reason,
        public string $created_at,
    ) {}

    public static function fromEntity(Refund $refund): self
    {
        return new self(
            id: $refund->uuid,
            payment_id: $refund->paymentId,
            amount: $refund->amount->amount,
            currency: $refund->amount->currency->code,
            status: $refund->status->value,
            reason: $refund->reason,
            created_at: $refund->createdAt->format('Y-m-d H:i:s')
        );
    }
}
