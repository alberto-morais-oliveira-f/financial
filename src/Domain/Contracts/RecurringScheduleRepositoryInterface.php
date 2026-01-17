<?php

namespace Am2tec\Financial\Domain\Contracts;

use Am2tec\Financial\Domain\Entities\RecurringSchedule;

interface RecurringScheduleRepositoryInterface
{
    public function save(RecurringSchedule $schedule): RecurringSchedule;
    public function findDue(): array;
}
