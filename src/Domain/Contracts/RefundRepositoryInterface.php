<?php

namespace Am2tec\Financial\Domain\Contracts;

use Am2tec\Financial\Domain\Entities\Refund;

interface RefundRepositoryInterface
{
    public function save(Refund $refund): Refund;
    public function findById(string $uuid): ?Refund;
    public function getTotalRefundedForPayment(string $paymentId): int;
}
