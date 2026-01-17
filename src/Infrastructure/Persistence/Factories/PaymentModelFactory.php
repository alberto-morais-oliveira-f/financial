<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Am2tec\Financial\Domain\Enums\PaymentStatus;
use Am2tec\Financial\Infrastructure\Persistence\Models\PaymentModel;

class PaymentModelFactory extends Factory
{
    protected $model = PaymentModel::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'gateway' => 'fake_gateway',
            'gateway_transaction_id' => 'ch_' . $this->faker->unique()->lexify('????????????????'),
            'currency' => 'BRL',
            'amount' => $this->faker->numberBetween(1000, 10000),
            'status' => PaymentStatus::PAID->value,
        ];
    }
}
