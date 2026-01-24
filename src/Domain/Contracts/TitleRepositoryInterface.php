<?php

namespace Am2tec\Financial\Domain\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface TitleRepositoryInterface
{
    public function findPendingDueUntil(\DateTimeInterface $date): Collection;
}
