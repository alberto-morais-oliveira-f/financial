<?php

namespace Am2tec\Financial\Infrastructure\Adapters;

use Am2tec\Financial\Application\DTOs\GatewayResponse;
use Am2tec\Financial\Application\DTOs\PaymentData;
use Am2tec\Financial\Domain\Contracts\PaymentGatewayAdapter;
use Am2tec\Financial\Domain\ValueObjects\Money;

class PagarmeAdapter implements PaymentGatewayAdapter
{
    public function charge(PaymentData $data): GatewayResponse
    {
        // TODO: Implement charge() method.
    }

    public function refund(string $gatewayTransactionId, ?Money $amount = null): GatewayResponse
    {
        // TODO: Implement refund() method.
    }
}
