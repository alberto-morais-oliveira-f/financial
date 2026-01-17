<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Am2tec\Financial\Domain\Contracts\TitleRepositoryInterface;
use Am2tec\Financial\Domain\Entities\Title;
use Am2tec\Financial\Domain\Enums\TitleStatus;
use Am2tec\Financial\Domain\Enums\TitleType;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Infrastructure\Persistence\Models\TitleModel;

class EloquentTitleRepository implements TitleRepositoryInterface
{
    public function save(Title $title): Title
    {
        $model = TitleModel::updateOrCreate(
            ['id' => $title->uuid],
            [
                'wallet_id' => $title->walletId,
                'type' => $title->type->value,
                'currency' => $title->amount->currency->code,
                'amount' => $title->amount->amount,
                'due_date' => $title->dueDate,
                'description' => $title->description,
                'status' => $title->status->value,
                'metadata' => $title->metadata,
                'created_at' => $title->createdAt,
            ]
        );

        return $this->toEntity($model);
    }

    public function findById(string $uuid): ?Title
    {
        $model = TitleModel::find($uuid);
        return $model ? $this->toEntity($model) : null;
    }

    public function findPendingDueUntil(\DateTimeInterface $date): array
    {
        $models = TitleModel::where('status', TitleStatus::PENDING->value)
            ->where('due_date', '<=', $date)
            ->get();

        return $models->map(fn($m) => $this->toEntity($m))->toArray();
    }

    private function toEntity(TitleModel $model): Title
    {
        return new Title(
            uuid: $model->id,
            walletId: $model->wallet_id,
            type: TitleType::from($model->type),
            amount: new Money($model->amount, new Currency($model->currency)),
            dueDate: \DateTimeImmutable::createFromMutable($model->due_date),
            description: $model->description,
            status: TitleStatus::from($model->status),
            createdAt: \DateTimeImmutable::createFromMutable($model->created_at),
            metadata: $model->metadata ?? []
        );
    }
}
