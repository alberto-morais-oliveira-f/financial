<?php

namespace Am2tec\Financial\Domain\Contracts;

use Am2tec\Financial\Infrastructure\Persistence\Models\PaymentModel;

interface PaymentRepositoryInterface
{
    public function findByGatewayId(string $gateway, string $gatewayTransactionId): ?PaymentModel;
}
