<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Am2tec\Financial\Domain\Contracts\RecurringScheduleRepositoryInterface;
use Am2tec\Financial\Domain\Entities\RecurringSchedule;
use Am2tec\Financial\Domain\Enums\ScheduleStatus;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Infrastructure\Persistence\Models\RecurringScheduleModel;

class EloquentRecurringScheduleRepository implements RecurringScheduleRepositoryInterface
{
    public function save(RecurringSchedule $schedule): RecurringSchedule
    {
        $model = RecurringScheduleModel::updateOrCreate(
            ['id' => $schedule->uuid],
            [
                'wallet_id' => $schedule->walletId,
                'currency' => $schedule->amount->currency->code,
                'amount' => $schedule->amount->amount,
                'cron_expression' => $schedule->cronExpression,
                'description' => $schedule->description,
                'next_run_at' => $schedule->nextRunAt,
                'ends_at' => $schedule->endsAt,
                'status' => $schedule->status->value,
                'metadata' => $schedule->metadata,
            ]
        );

        return $this->toEntity($model);
    }

    public function findDue(): array
    {
        $models = RecurringScheduleModel::where('status', ScheduleStatus::ACTIVE->value)
            ->where('next_run_at', '<=', now())
            ->get();

        return $models->map(fn($m) => $this->toEntity($m))->toArray();
    }

    private function toEntity(RecurringScheduleModel $model): RecurringSchedule
    {
        return new RecurringSchedule(
            uuid: $model->id,
            walletId: $model->wallet_id,
            amount: new Money($model->amount, new Currency($model->currency)),
            cronExpression: $model->cron_expression,
            description: $model->description,
            nextRunAt: \DateTimeImmutable::createFromMutable($model->next_run_at),
            status: ScheduleStatus::from($model->status),
            endsAt: $model->ends_at ? \DateTimeImmutable::createFromMutable($model->ends_at) : null,
            metadata: $model->metadata ?? []
        );
    }
}
