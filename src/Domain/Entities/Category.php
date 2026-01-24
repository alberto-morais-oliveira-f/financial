<?php

declare(strict_types=1);

namespace Am2tec\Financial\Domain\Entities;

use Am2tec\Financial\Domain\Enums\CategoryType;

class Category
{
    public function __construct(
        public readonly ?string $uuid,
        public readonly string $name,
        public readonly string $slug,
        public readonly CategoryType $type,
        public readonly ?string $description,
        public readonly ?string $parent_uuid = null
    ) {
    }
}
