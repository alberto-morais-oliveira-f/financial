<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Am2tec\Financial\Domain\Contracts\RefundRepositoryInterface;
use Am2tec\Financial\Domain\Entities\Refund;
use Am2tec\Financial\Domain\Enums\RefundStatus;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Infrastructure\Persistence\Models\RefundModel;

class EloquentRefundRepository implements RefundRepositoryInterface
{
    public function save(Refund $refund): Refund
    {
        $model = RefundModel::updateOrCreate(
            ['id' => $refund->uuid],
            [
                'payment_id' => $refund->paymentId,
                'currency' => $refund->amount->currency->code,
                'amount' => $refund->amount->amount,
                'status' => $refund->status->value,
                'gateway_refund_id' => $refund->gatewayRefundId,
                'reason' => $refund->reason,
                'failure_reason' => $refund->failureReason,
                'created_at' => $refund->createdAt,
            ]
        );

        return $this->toEntity($model);
    }

    public function findById(string $uuid): ?Refund
    {
        $model = RefundModel::find($uuid);
        return $model ? $this->toEntity($model) : null;
    }

    public function getTotalRefundedForPayment(string $paymentId): int
    {
        return RefundModel::where('payment_id', $paymentId)
            ->where('status', RefundStatus::PROCESSED->value)
            ->sum('amount');
    }

    private function toEntity(RefundModel $model): Refund
    {
        return new Refund(
            uuid: $model->id,
            paymentId: $model->payment_id,
            amount: new Money($model->amount, new Currency($model->currency)),
            status: RefundStatus::from($model->status),
            gatewayRefundId: $model->gateway_refund_id,
            reason: $model->reason,
            failureReason: $model->failure_reason,
            createdAt: \DateTimeImmutable::createFromMutable($model->created_at)
        );
    }
}
