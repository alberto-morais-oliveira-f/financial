<?php

namespace Am2tec\Financial\Application\DTOs;

use Spatie\LaravelData\Data;

class DreData extends Data
{
    public function __construct(
        public readonly int $category_id,
        public readonly string $category_name,
        public readonly string $category_type,
        public readonly ?int $parent_id,
        public readonly float $total_amount,
    ) {
    }
}
