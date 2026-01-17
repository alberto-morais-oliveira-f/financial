<?php

namespace Am2tec\Financial\Domain\ValueObjects;

class Owner
{
    public function __construct(
        private readonly string|int $id,
        private readonly string $type
    ) {}

    public function getOwnerId(): string|int
    {
        return $this->id;
    }

    public function getOwnerType(): string
    {
        return $this->type;
    }
}
