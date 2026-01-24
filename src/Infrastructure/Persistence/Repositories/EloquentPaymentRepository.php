<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Am2tec\Financial\Domain\Contracts\PaymentRepositoryInterface;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Infrastructure\Persistence\Models\PaymentModel;

class EloquentPaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(PaymentModel $model)
    {
        parent::__construct($model);
    }

    public function findByGatewayId(string $gateway, string $gatewayTransactionId): ?PaymentModel
    {
        return PaymentModel::where('gateway', $gateway)
            ->where('gateway_transaction_id', $gatewayTransactionId)
            ->first();
    }
}
