<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Am2tec\Financial\Domain\Contracts\WalletRepositoryInterface;
use Am2tec\Financial\Domain\ValueObjects\Owner;
use Am2tec\Financial\Infrastructure\Persistence\Models\WalletModel;
use Illuminate\Database\Eloquent\Collection;

class EloquentWalletRepository extends BaseRepository implements WalletRepositoryInterface
{
    public function __construct(WalletModel $model)
    {
        parent::__construct($model);
    }

    public function findByOwner(Owner $owner): Collection
    {
        return $this->model->where('owner_id', $owner->getOwnerId())
            ->where('owner_type', $owner->getOwnerType())->get();
    }
}
