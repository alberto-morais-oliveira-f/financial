<?php

namespace Am2tec\Financial\Application\Api\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Am2tec\Financial\Domain\Entities\Wallet;
use App\Models\User; // Assuming the consumer app has a User model

class WalletPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the wallet.
     *
     * @param  \App\Models\User  $user
     * @param  \Am2tec\Financial\Domain\Entities\Wallet  $wallet
     * @return bool
     */
    public function view(User $user, Wallet $wallet): bool
    {
        // The user can only view a wallet if they are the owner of it.
        return $user->id == $wallet->owner->getOwnerId() && 
               get_class($user) == $wallet->owner->getOwnerType();
    }
}
