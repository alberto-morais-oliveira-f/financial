<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Am2tec\Financial\Domain\Contracts\RefundRepositoryInterface;
use Am2tec\Financial\Domain\Entities\Refund;
use Am2tec\Financial\Domain\Enums\RefundStatus;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Infrastructure\Persistence\Models\RefundModel;

class EloquentRefundRepository extends BaseRepository implements RefundRepositoryInterface
{
    public function __construct(RefundModel $model)
    {
        parent::__construct($model);
    }

    public function getTotalRefundedForPayment(string $paymentId): int
    {
        return $this->model->where('payment_id', $paymentId)
            ->where('status', RefundStatus::PROCESSED->value)
            ->sum('amount');
    }
}
