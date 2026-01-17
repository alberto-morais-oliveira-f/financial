<?php

namespace Am2tec\Financial\Application\DTOs;

use Spatie\LaravelData\Data;
use Am2tec\Financial\Domain\ValueObjects\Money;

class PaymentData extends Data
{
    public function __construct(
        public Money $amount,
        public string $token, // Token do cartão ou identificador do método
        public string $description,
        public ?string $payerEmail = null,
        public ?string $payerName = null,
        public array $metadata = []
    ) {}
}
