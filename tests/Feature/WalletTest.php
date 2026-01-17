<?php

namespace Am2tec\Financial\Tests\Feature;

use Am2tec\Financial\Infrastructure\Persistence\Models\WalletModel;
use Am2tec\Financial\Tests\Support\User;
use Am2tec\Financial\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_retrieve_a_wallet_by_id()
    {
        $user = User::factory()->create();
        $wallet = WalletModel::factory()->create([
            'owner_id' => $user->id,
            'owner_type' => get_class($user),
            'status' => 'active', // Garantir um status conhecido
        ]);

        $this->actingAs($user);

        $response = $this->getJson("/api/financial/wallets/{$wallet->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $wallet->id,
                     'name' => $wallet->name,
                     'status' => 'active', // CORREÇÃO: Verificar o status
                 ]);
    }

    /** @test */
    public function it_returns_404_when_wallet_not_found()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->getJson("/api/financial/wallets/999999");

        $response->assertStatus(404);
    }
}
