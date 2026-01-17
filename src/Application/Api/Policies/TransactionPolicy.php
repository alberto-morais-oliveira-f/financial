<?php

namespace Am2tec\Financial\Application\Api\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Am2tec\Financial\Domain\Contracts\WalletRepositoryInterface;
use App\Models\User; // Assuming the consumer app has a User model

class TransactionPolicy
{
    use HandlesAuthorization;

    public function __construct(
        protected WalletRepositoryInterface $walletRepository
    ) {}

    /**
     * Determine whether the user can create a transaction from a specific wallet.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @param  string  $fromWalletId
     * @return bool
     */
    public function create(User $user, string $fromWalletId): bool
    {
        $wallet = $this->walletRepository->findById($fromWalletId);

        if (!$wallet) {
            return false; // Or handle as a different type of error, but for authorization, this is a denial.
        }

        // The user can only create a transaction if they own the source wallet.
        return $user->id == $wallet->owner->getOwnerId() &&
               get_class($user) == $wallet->owner->getOwnerType();
    }
}
