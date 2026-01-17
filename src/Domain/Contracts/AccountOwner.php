<?php

namespace Am2tec\Financial\Domain\Contracts;

interface AccountOwner
{
    public function getOwnerId(): string|int;
    public function getOwnerType(): string;
    public function getOwnerName(): string;
    public function getOwnerEmail(): ?string;
}
