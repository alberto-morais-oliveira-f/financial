<?php

namespace Am2tec\Financial\Application\Api\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Am2tec\Financial\Domain\Entities\Wallet;
use Illuminate\Contracts\Auth\Authenticatable;

class WalletPolicy
{
    use HandlesAuthorization;

    public function view(Authenticatable $user, Wallet $wallet): bool
    {
        // CORREÇÃO: Usar a estrutura correta com o Value Object
        return $user->getAuthIdentifier() == $wallet->owner->getOwnerId() &&
               get_class($user) == $wallet->owner->getOwnerType();
    }
}
