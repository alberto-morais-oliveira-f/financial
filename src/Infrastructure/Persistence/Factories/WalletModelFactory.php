<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Factories;

use Am2tec\Financial\Infrastructure\Persistence\Models\WalletModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletModelFactory extends Factory
{
    protected $model = WalletModel::class;

    public function definition(): array
    {
        return [
            'owner_type' => 'user',
            'owner_id' => $this->faker->uuid,
            'currency' => 'BRL',
            'balance' => $this->faker->numberBetween(0, 1000000),
            'status' => 'active',
            'name' => $this->faker->company . ' Wallet',
        ];
    }
}
