<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Am2tec\Financial\Domain\Contracts\PaymentRepositoryInterface;
use Am2tec\Financial\Domain\Entities\Payment;
use Am2tec\Financial\Domain\Enums\PaymentStatus;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Infrastructure\Persistence\Models\PaymentModel;

class EloquentPaymentRepository implements PaymentRepositoryInterface
{
    public function save(Payment $payment): Payment
    {
        $model = PaymentModel::updateOrCreate(
            ['id' => $payment->uuid],
            [
                'gateway' => $payment->gateway,
                'gateway_transaction_id' => $payment->gatewayTransactionId,
                'currency' => $payment->amount->currency->code,
                'amount' => $payment->amount->amount,
                'status' => $payment->status->value,
                'error_message' => $payment->errorMessage,
                'created_at' => $payment->createdAt,
            ]
        );

        return $this->toEntity($model);
    }

    public function findById(string $uuid): ?Payment
    {
        $model = PaymentModel::find($uuid);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByGatewayId(string $gateway, string $gatewayTransactionId): ?Payment
    {
        $model = PaymentModel::where('gateway', $gateway)
            ->where('gateway_transaction_id', $gatewayTransactionId)
            ->first();
            
        return $model ? $this->toEntity($model) : null;
    }

    private function toEntity(PaymentModel $model): Payment
    {
        return new Payment(
            uuid: $model->id,
            gateway: $model->gateway,
            gatewayTransactionId: $model->gateway_transaction_id,
            amount: new Money($model->amount, new Currency($model->currency)),
            status: PaymentStatus::from($model->status),
            createdAt: \DateTimeImmutable::createFromMutable($model->created_at),
            errorMessage: $model->error_message
        );
    }
}
