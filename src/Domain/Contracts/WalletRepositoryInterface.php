<?php

namespace Am2tec\Financial\Domain\Contracts;

use Am2tec\Financial\Domain\ValueObjects\Owner;
use Illuminate\Database\Eloquent\Collection;

interface WalletRepositoryInterface
{
    public function findByOwner(Owner $owner): Collection;
}
