<?php

namespace Am2tec\Financial\Domain\Contracts;

use Am2tec\Financial\Application\DTOs\PaymentData;
use Am2tec\Financial\Application\DTOs\GatewayResponse;
use Am2tec\Financial\Domain\ValueObjects\Money;

interface PaymentGatewayAdapter
{
    public function charge(PaymentData $data): GatewayResponse;
    public function refund(string $gatewayTransactionId, ?Money $amount = null): GatewayResponse;
}
