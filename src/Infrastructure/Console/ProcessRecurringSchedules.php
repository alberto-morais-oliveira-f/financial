<?php

namespace Am2tec\Financial\Infrastructure\Console;

use Illuminate\Console\Command;
use Am2tec\Financial\Domain\Services\RecurringService;

class ProcessRecurringSchedules extends Command
{
    protected $signature = 'financial:process-recurring';
    protected $description = 'Process due recurring schedules to create titles.';

    public function handle(RecurringService $recurringService): int
    {
        $this->info('Processing due recurring schedules...');

        $count = $recurringService->processDueSchedules();

        $this->info("Successfully processed {$count} schedules.");

        return self::SUCCESS;
    }
}
