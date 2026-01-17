<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Am2tec\Financial\Domain\Contracts\WalletRepositoryInterface;
use Am2tec\Financial\Domain\Entities\Wallet;
use Am2tec\Financial\Domain\Enums\WalletStatus;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Domain\ValueObjects\Owner;
use Am2tec\Financial\Infrastructure\Persistence\Models\WalletModel;

class EloquentWalletRepository implements WalletRepositoryInterface
{
    public function findById(string $uuid): ?Wallet
    {
        $model = WalletModel::find($uuid);
        return $model ? $this->toEntity($model) : null;
    }

    public function save(Wallet $wallet): Wallet
    {
        $model = WalletModel::updateOrCreate(
            ['id' => $wallet->uuid],
            [
                'owner_type' => $wallet->owner->getOwnerType(),
                'owner_id' => $wallet->owner->getOwnerId(),
                'name' => $wallet->name,
                'balance' => $wallet->balance->amount,
                'currency' => $wallet->balance->currency->code,
                'status' => $wallet->status->value, // CORREÇÃO: Adicionado
            ]
        );

        return $this->toEntity($model->fresh());
    }

    public function findByOwner(Owner $owner): array
    {
        $models = WalletModel::where('owner_type', $owner->getOwnerType())
            ->where('owner_id', $owner->getOwnerId())
            ->get();

        return $models->map(fn($model) => $this->toEntity($model))->all();
    }

    private function toEntity(WalletModel $model): Wallet
    {
        $owner = new Owner($model->owner_id, $model->owner_type);
        $balance = new Money($model->balance, new Currency($model->currency));

        return new Wallet(
            uuid: $model->id,
            name: $model->name,
            balance: $balance,
            owner: $owner,
            status: WalletStatus::from($model->status) // CORREÇÃO: Adicionado
        );
    }
}
