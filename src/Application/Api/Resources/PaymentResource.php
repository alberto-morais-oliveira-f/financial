<?php

namespace Am2tec\Financial\Application\Api\Resources;

use Spatie\LaravelData\Data;
use Am2tec\Financial\Domain\Entities\Payment;

class PaymentResource extends Data
{
    public function __construct(
        public string $id,
        public string $gateway,
        public ?string $gateway_transaction_id,
        public int $amount,
        public string $currency,
        public string $status,
        public ?string $error_message,
        public string $created_at,
    ) {}

    public static function fromEntity(Payment $payment): self
    {
        return new self(
            id: $payment->uuid,
            gateway: $payment->gateway,
            gateway_transaction_id: $payment->gatewayTransactionId,
            amount: $payment->amount->amount,
            currency: $payment->amount->currency->code,
            status: $payment->status->value,
            error_message: $payment->errorMessage,
            created_at: $payment->createdAt->format('Y-m-d H:i:s')
        );
    }
}
