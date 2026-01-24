<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Am2tec\Financial\Domain\Contracts\RecurringScheduleRepositoryInterface;
use Am2tec\Financial\Domain\Enums\ScheduleStatus;
use Am2tec\Financial\Infrastructure\Persistence\Models\RecurringScheduleModel;
use Illuminate\Database\Eloquent\Collection;

class EloquentRecurringScheduleRepository extends BaseRepository implements RecurringScheduleRepositoryInterface
{
    public function findDue(): Collection
    {
        return RecurringScheduleModel::where('status', ScheduleStatus::ACTIVE->value)
            ->where('next_run_at', '<=', now())
            ->get();
    }
}
