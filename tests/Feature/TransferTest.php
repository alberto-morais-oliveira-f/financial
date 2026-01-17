<?php

namespace Am2tec\Financial\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Am2tec\Financial\Domain\Contracts\AccountOwner;
use Am2tec\Financial\Domain\Services\TransactionService;
use Am2tec\Financial\Domain\Services\WalletService;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Tests\TestCase;

class TransferTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_transfer_money_between_wallets()
    {
        $walletService = app(WalletService::class);
        $transactionService = app(TransactionService::class);

        // Create Owners (Mock)
        $owner1 = new class implements AccountOwner {
            public function getOwnerId(): string|int { return 'user-1'; }
            public function getOwnerType(): string { return 'User'; }
            public function getOwnerName(): string { return 'Alice'; }
            public function getOwnerEmail(): ?string { return null; }
        };

        $owner2 = new class implements AccountOwner {
            public function getOwnerId(): string|int { return 'user-2'; }
            public function getOwnerType(): string { return 'User'; }
            public function getOwnerName(): string { return 'Bob'; }
            public function getOwnerEmail(): ?string { return null; }
        };

        // Create Wallets
        $wallet1 = $walletService->createWallet($owner1, 'Alice Wallet');
        $wallet2 = $walletService->createWallet($owner2, 'Bob Wallet');

        // Initial Deposit (Hack for test: manually update balance)
        // In real life we would have a "Deposit" transaction type
        $wallet1->deposit(Money::of(1000, Currency::BRL()));
        app(\Am2tec\Financial\Domain\Contracts\WalletRepositoryInterface::class)->save($wallet1);

        // Perform Transfer
        $transactionService->transfer(
            $wallet1->uuid,
            $wallet2->uuid,
            Money::of(200, Currency::BRL()),
            'Payment for services'
        );

        // Assert Balances
        $this->assertEquals(800, $walletService->getBalance($wallet1->uuid)->amount);
        $this->assertEquals(200, $walletService->getBalance($wallet2->uuid)->amount);
    }
}
