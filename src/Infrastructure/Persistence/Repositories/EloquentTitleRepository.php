<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Am2tec\Financial\Domain\Contracts\TitleRepositoryInterface;
use Am2tec\Financial\Domain\Entities\Title;
use Am2tec\Financial\Domain\Enums\TitleStatus;
use Am2tec\Financial\Domain\Enums\TitleType;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Infrastructure\Persistence\Models\TitleModel;
use Illuminate\Database\Eloquent\Collection;

class EloquentTitleRepository extends BaseRepository implements TitleRepositoryInterface
{
    public function __construct(TitleModel $model)
    {
        parent::__construct($model);
    }

    public function findPendingDueUntil(\DateTimeInterface $date): Collection
    {
        return $this->model->where('status', TitleStatus::PENDING->value)
            ->where('due_date', '<=', $date)
            ->get();
    }
}
