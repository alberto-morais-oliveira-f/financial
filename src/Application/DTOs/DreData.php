<?php

namespace Am2tec\Financial\Application\DTOs;

use Spatie\LaravelData\Data;

class DreData extends Data
{
    public function __construct(
        // CORREÇÃO: category_id é uma string (UUID)
        public readonly string $category_id,
        public readonly string $category_name,
        public readonly string $category_type,
        // CORREÇÃO: parent_id é uma string (UUID) ou nulo
        public readonly ?string $parent_id,
        public readonly float $total_amount,
    ) {
    }
}
