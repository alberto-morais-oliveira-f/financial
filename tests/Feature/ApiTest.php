<?php

namespace Am2tec\Financial\Tests\Feature;

use Am2tec\Financial\Infrastructure\Persistence\Models\WalletModel;
use Am2tec\Financial\Tests\Support\User; // CORREÇÃO
use Am2tec\Financial\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function test_user_can_view_their_own_wallet()
    {
        $user = User::factory()->create();
        $wallet = WalletModel::factory()->create(['owner_id' => $user->id, 'owner_type' => get_class($user)]);

        $this->actingAs($user);

        $response = $this->getJson("/api/financial/wallets/{$wallet->id}");

        $response->assertStatus(200);
        $response->assertJson(['id' => $wallet->id]);
    }

    /** @test */
    public function test_user_cannot_view_another_users_wallet()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $wallet = WalletModel::factory()->create(['owner_id' => $user2->id, 'owner_type' => get_class($user2)]);

        $this->actingAs($user1);

        $response = $this->getJson("/api/financial/wallets/{$wallet->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function test_user_can_create_transaction_from_their_own_wallet()
    {
        $user = User::factory()->create();
        $wallet1 = WalletModel::factory()->create(['owner_id' => $user->id, 'owner_type' => get_class($user)]);
        $wallet2 = WalletModel::factory()->create(['owner_id' => $user->id, 'owner_type' => get_class($user)]);

        $this->actingAs($user);

        $response = $this->postJson('/api/financial/transactions/transfer', [
            'from_wallet_id' => $wallet1->id,
            'to_wallet_id' => $wallet2->id,
            'amount' => 1000,
            'description' => 'Test Transfer'
        ]);
        $response->assertStatus(201);
    }

    /** @test */
    public function test_user_cannot_create_transaction_from_another_users_wallet()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $wallet1 = WalletModel::factory()->create(['owner_id' => $user2->id, 'owner_type' => get_class($user2)]);
        $wallet2 = WalletModel::factory()->create(['owner_id' => $user1->id, 'owner_type' => get_class($user1)]);

        $this->actingAs($user1);

        $response = $this->postJson('/api/financial/transactions/transfer', [
            'from_wallet_id' => $wallet1->id,
            'to_wallet_id' => $wallet2->id,
            'amount' => 1000,
            'description' => 'Test Transfer'
        ]);

        $response->assertStatus(403);
    }
}
