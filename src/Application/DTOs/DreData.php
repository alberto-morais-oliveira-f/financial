<?php

namespace Am2tec\Financial\Application\DTOs;

// Removendo a dependência de Spatie\LaravelData\Data
// use Spatie\LaravelData\Data;

class DreData // Não estende mais Data
{
    public function __construct(
        public readonly string $category_id,
        public readonly string $category_name,
        public readonly string $category_type,
        public readonly ?string $parent_id,
        public readonly float $total_amount,
    ) {
    }

    // Adicionando um método estático para criar a partir de um array/objeto,
    // simulando o comportamento de Data::from() mas sem a complexidade da biblioteca.
    public static function from(object|array $data): self
    {
        return new self(
            category_id: (string) $data->category_id,
            category_name: (string) $data->category_name,
            category_type: (string) $data->category_type,
            parent_id: isset($data->parent_id) ? (string) $data->parent_id : null,
            total_amount: (float) $data->total_amount
        );
    }
}
