<?php

namespace Am2tec\Financial\Domain\Contracts;

use Am2tec\Financial\Domain\Entities\Payment;

interface PaymentRepositoryInterface
{
    public function save(Payment $payment): Payment;
    public function findById(string $uuid): ?Payment;
    public function findByGatewayId(string $gateway, string $gatewayTransactionId): ?Payment;
}
