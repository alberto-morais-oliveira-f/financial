<?php

namespace Am2tec\Financial\Application\Api\Data;

use Am2tec\Financial\Domain\Enums\CategoryType;
use Spatie\LaravelData\Data;

class CategoryData extends Data
{
    public function __construct(
        public string $uuid,
        public ?string $parent_uuid,
        public string $name,
        public string $slug,
        public CategoryType $type,
        public ?string $description,
    ) {
    }
}
