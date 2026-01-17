<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Factories;

use Am2tec\Financial\Infrastructure\Persistence\Models\EntryModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntryModelFactory extends Factory
{
    protected $model = EntryModel::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['credit', 'debit']),
            'amount' => $this->faker->numberBetween(1000, 100000),
            // Nota: transaction_id, wallet_id e category_uuid devem ser
            // fornecidos ao criar a factory no teste, pois são chaves estrangeiras.
        ];
    }
}
