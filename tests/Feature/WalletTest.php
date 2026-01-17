<?php

namespace Am2tec\Financial\Tests\Feature;

use Am2tec\Financial\Infrastructure\Persistence\Models\Wallet;
use Am2tec\Financial\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate', ['--database' => 'testing'])->run();
    }

    /** @test */
    public function it_can_retrieve_a_wallet_by_id()
    {
        // Arrange
        $wallet = Wallet::factory()->create();

        // Act
        $response = $this->getJson("/api/financial/wallets/{$wallet->id}");

        // Assert
        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $wallet->id,
                     'name' => $wallet->name,
                     'balance' => $wallet->balance,
                 ]);
    }

    /** @test */
    public function it_returns_404_when_wallet_not_found()
    {
        // Act
        $response = $this->getJson("/api/financial/wallets/999999");

        // Assert
        $response->assertStatus(404)
                 ->assertJson(['message' => 'Wallet with ID [999999] not found.']);
    }
}
