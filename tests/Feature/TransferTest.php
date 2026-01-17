<?php

namespace Am2tec\Financial\Tests\Feature;

use Am2tec\Financial\Domain\Entities\Transaction;
use Am2tec\Financial\Domain\Exceptions\InsufficientFundsException;
use Am2tec\Financial\Domain\Exceptions\WalletNotFoundException;
use Am2tec\Financial\Domain\Services\TransactionService;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money; // CORREÇÃO
use Am2tec\Financial\Infrastructure\Persistence\Models\WalletModel;
use Am2tec\Financial\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransferTest extends TestCase
{
    use RefreshDatabase;

    private TransactionService $transactionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transactionService = $this->app->make(TransactionService::class);
        $this->currency = new Currency(config('financial.default_currency', 'BRL'));
    }

    /** @test */
    public function test_can_transfer_money_between_wallets()
    {
        $wallet1 = WalletModel::factory()->create(['balance' => 10000]);
        $wallet2 = WalletModel::factory()->create(['balance' => 5000]);
        $amount = new Money(2000, $this->currency); // CORREÇÃO

        $transaction = $this->transactionService->transfer($wallet1->id, $wallet2->id, $amount, 'Test Transfer');

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertDatabaseHas('fin_wallets', ['id' => $wallet1->id, 'balance' => 8000]);
        $this->assertDatabaseHas('fin_wallets', ['id' => $wallet2->id, 'balance' => 7000]);
    }

    /** @test */
    public function test_cannot_transfer_from_non_existent_wallet()
    {
        $this->expectException(WalletNotFoundException::class);
        $wallet2 = WalletModel::factory()->create();
        $amount = new Money(2000, $this->currency); // CORREÇÃO

        $this->transactionService->transfer('non-existent-id', $wallet2->id, $amount, 'Test Transfer');
    }

    /** @test */
    public function test_cannot_transfer_with_insufficient_funds()
    {
        $this->expectException(InsufficientFundsException::class);
        $wallet1 = WalletModel::factory()->create(['balance' => 1000]);
        $wallet2 = WalletModel::factory()->create();
        $amount = new Money(2000, $this->currency); // CORREÇÃO

        $this->transactionService->transfer($wallet1->id, $wallet2->id, $amount, 'Test Transfer');
    }
}
