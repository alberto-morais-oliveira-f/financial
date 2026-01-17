<?php

namespace Am2tec\Financial\Infrastructure\Persistence\Repositories;

use Am2tec\Financial\Domain\Contracts\AccountOwner;
use Am2tec\Financial\Domain\Contracts\WalletRepositoryInterface;
use Am2tec\Financial\Domain\Entities\Wallet;
use Am2tec\Financial\Domain\Enums\WalletStatus;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Infrastructure\Persistence\Models\WalletModel;

class EloquentWalletRepository implements WalletRepositoryInterface
{
    public function save(Wallet $wallet): Wallet
    {
        $model = WalletModel::updateOrCreate(
            ['id' => $wallet->uuid],
            [
                'owner_type' => $wallet->owner->getOwnerType(),
                'owner_id' => $wallet->owner->getOwnerId(),
                'currency' => $wallet->currency->code,
                'balance' => $wallet->balance->amount,
                'status' => $wallet->status->value,
                'name' => $wallet->name,
                'description' => $wallet->description,
            ]
        );

        return $this->toEntity($model, $wallet->owner);
    }

    public function findById(string $uuid): ?Wallet
    {
        $model = WalletModel::find($uuid);
        if (!$model) {
            return null;
        }

        // Note: Reconstructing the Owner here is tricky without knowing the concrete class.
        // In a real scenario, we might need a factory or just return a generic owner wrapper.
        // For now, I'll assume we can't fully reconstruct the Owner object from just type/id 
        // without a resolver service, so I'll create a simple anonymous class or DTO.
        
        $owner = new class($model->owner_id, $model->owner_type) implements AccountOwner {
            public function __construct(public $id, public $type) {}
            public function getOwnerId(): string|int { return $this->id; }
            public function getOwnerType(): string { return $this->type; }
            public function getOwnerName(): string { return 'Unknown'; }
            public function getOwnerEmail(): ?string { return null; }
        };

        return $this->toEntity($model, $owner);
    }

    public function findByOwner(AccountOwner $owner): array
    {
        $models = WalletModel::where('owner_type', $owner->getOwnerType())
            ->where('owner_id', $owner->getOwnerId())
            ->get();

        return $models->map(fn($m) => $this->toEntity($m, $owner))->toArray();
    }

    private function toEntity(WalletModel $model, AccountOwner $owner): Wallet
    {
        return new Wallet(
            uuid: $model->id,
            owner: $owner,
            currency: new Currency($model->currency),
            balance: new Money($model->balance, new Currency($model->currency)),
            status: WalletStatus::from($model->status),
            name: $model->name,
            description: $model->description
        );
    }
}
