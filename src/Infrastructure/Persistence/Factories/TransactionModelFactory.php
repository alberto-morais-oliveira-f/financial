<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Factories;

use Am2tec\Financial\Infrastructure\Persistence\Models\TransactionModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionModelFactory extends Factory
{
    protected $model = TransactionModel::class;

    public function definition(): array
    {
        return [
            // CORREÇÃO: Adicionar um valor para a coluna obrigatória 'reference_code'
            'reference_code' => $this->faker->unique()->regexify('[A-Z0-9]{10}'),
            'status' => 'POSTED',
            'description' => $this->faker->sentence,
            'metadata' => [],
        ];
    }
}
