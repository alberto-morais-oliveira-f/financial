<?php

namespace Am2tec\Financial\Application\Api\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Am2tec\Financial\Domain\Contracts\WalletRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;

class TransactionPolicy
{
    use HandlesAuthorization;

    public function __construct(
        protected WalletRepositoryInterface $walletRepository
    ) {}

    public function create(Authenticatable $user, string $fromWalletId): bool
    {
        $wallet = $this->walletRepository->findById($fromWalletId);

        if (!$wallet) {
            return false;
        }

        // CORREÇÃO: Usar a estrutura correta com o Value Object
        return $user->getAuthIdentifier() == $wallet->owner->getOwnerId() &&
               get_class($user) == $wallet->owner->getOwnerType();
    }
}
