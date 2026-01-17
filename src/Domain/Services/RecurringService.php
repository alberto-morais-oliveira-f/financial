<?php

namespace Am2tec\Financial\Domain\Services;

use Cron\CronExpression;
use Illuminate\Support\Str;
use Am2tec\Financial\Domain\Contracts\RecurringScheduleRepositoryInterface;
use Am2tec\Financial\Domain\Contracts\TitleRepositoryInterface;
use Am2tec\Financial\Domain\Entities\Title;
use Am2tec\Financial\Domain\Enums\TitleType;
use Am2tec\Financial\Domain\Events\TitleCreated;

class RecurringService
{
    public function __construct(
        protected RecurringScheduleRepositoryInterface $scheduleRepository,
        protected TitleRepositoryInterface $titleRepository
    ) {}

    public function processDueSchedules(): int
    {
        $dueSchedules = $this->scheduleRepository->findDue();
        $count = 0;

        foreach ($dueSchedules as $schedule) {
            $title = new Title(
                uuid: Str::uuid()->toString(),
                walletId: $schedule->walletId,
                type: TitleType::RECEIVABLE,
                amount: $schedule->amount,
                dueDate: $schedule->nextRunAt,
                description: $schedule->description . ' - ' . $schedule->nextRunAt->format('Y-m-d')
            );

            $this->titleRepository->save($title);
            TitleCreated::dispatch($title);

            $cron = new CronExpression($schedule->cronExpression);
            $nextRun = $cron->getNextRunDate($schedule->nextRunAt);
            
            $schedule->updateNextRunDate(\DateTimeImmutable::createFromMutable($nextRun));
            $this->scheduleRepository->save($schedule);

            $count++;
        }

        return $count;
    }
}
