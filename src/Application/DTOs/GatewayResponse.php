<?php

namespace Am2tec\Financial\Application\DTOs;

use Spatie\LaravelData\Data;
use Am2tec\Financial\Domain\Enums\PaymentStatus;

class GatewayResponse extends Data
{
    public function __construct(
        public string $gatewayTransactionId,
        public PaymentStatus $status,
        public ?string $rawResponse = null,
        public ?string $errorMessage = null
    ) {}
}
