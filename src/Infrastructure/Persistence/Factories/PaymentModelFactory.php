<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Factories;

use Am2tec\Financial\Domain\Enums\PaymentStatus;
use Am2tec\Financial\Infrastructure\Persistence\Models\PaymentModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentModelFactory extends Factory
{
    protected $model = PaymentModel::class;

    public function definition(): array
    {
        return [
            'gateway' => 'test_gateway',
            'gateway_transaction_id' => $this->faker->unique()->uuid,
            'amount' => $this->faker->numberBetween(1000, 50000),
            'status' => PaymentStatus::PAID,
            'currency' => 'BRL', // CORREÇÃO
        ];
    }
}
