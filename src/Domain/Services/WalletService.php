<?php

namespace Am2tec\Financial\Domain\Services;

use Am2tec\Financial\Domain\Contracts\WalletRepositoryInterface;
use Am2tec\Financial\Domain\Entities\Wallet;
use Am2tec\Financial\Domain\Exceptions\WalletNotFoundException;

class WalletService
{
    public function __construct(protected WalletRepositoryInterface $walletRepository)
    {
    }

    /**
     * Find a wallet by its ID.
     *
     * @param string $id
     * @return Wallet
     * @throws WalletNotFoundException
     */
    public function findById(string $id): Wallet
    {
        $wallet = $this->walletRepository->findById($id);

        if (!$wallet) {
            throw new WalletNotFoundException("Wallet with ID [{$id}] not found.");
        }

        return $wallet;
    }
}
