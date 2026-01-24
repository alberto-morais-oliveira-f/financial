<?php

namespace Am2tec\Financial\Domain\Contracts;

use Am2tec\Financial\Domain\Entities\Refund;

interface RefundRepositoryInterface
{
    public function getTotalRefundedForPayment(string $paymentId): int;
}
