<?php

namespace Am2tec\Financial\Domain\Entities;

use Am2tec\Financial\Domain\Enums\CategoryType;
use Spatie\LaravelData\Data;

class Category extends Data
{
    public function __construct(
        public string $uuid,
        public ?string $parent_uuid,
        public string $name,
        public string $slug,
        public CategoryType $type,
        public bool $is_system_category,
        public ?string $description,
        public mixed $created_at,
        public mixed $updated_at,
    ) {
    }
}
