<?php

namespace Am2tec\Financial\Domain\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface RecurringScheduleRepositoryInterface
{
    public function findDue(): Collection;
}
