<?php

namespace Am2tec\Financial\Infrastructure\Console\Commands;

use Illuminate\Console\Command;

class ProcessRecurringSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'financial:process-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process financial recurring schedules that are due.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Processing recurring schedules...');
        // A lógica para processar os agendamentos virá aqui.
        $this->info('Done.');
        
        return Command::SUCCESS;
    }
}
