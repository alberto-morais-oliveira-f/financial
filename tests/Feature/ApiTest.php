<?php

namespace Am2tec\Financial\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Am2tec\Financial\Domain\Contracts\AccountOwner;
use Am2tec\Financial\Domain\Services\WalletService;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Tests\TestCase;
use App\Models\User; // Assuming a User model exists in the consuming app

class ApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // We need a User model for authentication. Since it's in the consumer app,
        // we'll create a temporary one for testing purposes.
        if (!class_exists(User::class)) {
            $this->artisan('make:model User');
        }
        $this->artisan('migrate');
    }

    public function test_user_can_view_their_own_wallet()
    {
        $user = User::factory()->create();
        $walletService = app(WalletService::class);

        $owner = new class($user) implements AccountOwner {
            public function __construct(public User $user) {}
            public function getOwnerId(): string|int { return $this->user->id; }
            public function getOwnerType(): string { return get_class($this->user); }
            public function getOwnerName(): string { return 'Test'; }
            public function getOwnerEmail(): ?string { return null; }
        };

        $wallet = $walletService->createWallet($owner, 'My Wallet');

        $response = $this->actingAs($user)->getJson("/api/financial/wallets/{$wallet->uuid}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $wallet->uuid]);
    }

    public function test_user_cannot_view_another_users_wallet()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $walletService = app(WalletService::class);

        $owner1 = new class($user1) implements AccountOwner {
            public function __construct(public User $user) {}
            public function getOwnerId(): string|int { return $this->user->id; }
            public function getOwnerType(): string { return get_class($this->user); }
            public function getOwnerName(): string { return 'Test'; }
            public function getOwnerEmail(): ?string { return null; }
        };

        $wallet = $walletService->createWallet($owner1, 'User1 Wallet');

        $response = $this->actingAs($user2)->getJson("/api/financial/wallets/{$wallet->uuid}");

        $response->assertStatus(403);
    }

    public function test_user_can_create_transaction_from_their_own_wallet()
    {
        $user = User::factory()->create();
        $walletService = app(WalletService::class);

        $owner = new class($user) implements AccountOwner {
            public function __construct(public User $user) {}
            public function getOwnerId(): string|int { return $this->user->id; }
            public function getOwnerType(): string { return get_class($this->user); }
            public function getOwnerName(): string { return 'Test'; }
            public function getOwnerEmail(): ?string { return null; }
        };

        $wallet1 = $walletService->createWallet($owner, 'My Wallet 1');
        $wallet2 = $walletService->createWallet($owner, 'My Wallet 2');
        
        // Give wallet1 some funds
        $wallet1->deposit(Money::of(1000, Currency::BRL()));
        app(\Am2tec\Financial\Domain\Contracts\WalletRepositoryInterface::class)->save($wallet1);

        $payload = [
            'from_wallet_id' => $wallet1->uuid,
            'to_wallet_id' => $wallet2->uuid,
            'amount' => 500,
            'description' => 'Test Transfer'
        ];

        $response = $this->actingAs($user)->postJson('/api/financial/transactions/transfer', $payload);

        $response->assertStatus(200)
                 ->assertJsonStructure(['id', 'description', 'status', 'entries']);
    }

    public function test_user_cannot_create_transaction_from_another_users_wallet()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $walletService = app(WalletService::class);

        $owner1 = new class($user1) implements AccountOwner {
            public function __construct(public User $user) {}
            public function getOwnerId(): string|int { return $this->user->id; }
            public function getOwnerType(): string { return get_class($this->user); }
            public function getOwnerName(): string { return 'Test'; }
            public function getOwnerEmail(): ?string { return null; }
        };
        
        $owner2 = new class($user2) implements AccountOwner {
            public function __construct(public User $user) {}
            public function getOwnerId(): string|int { return $this->user->id; }
            public function getOwnerType(): string { return get_class($this->user); }
            public function getOwnerName(): string { return 'Test'; }
            public function getOwnerEmail(): ?string { return null; }
        };

        $wallet1 = $walletService->createWallet($owner1, 'User1 Wallet');
        $wallet2 = $walletService->createWallet($owner2, 'User2 Wallet');

        $payload = [
            'from_wallet_id' => $wallet1->uuid,
            'to_wallet_id' => $wallet2->uuid,
            'amount' => 500,
            'description' => 'Malicious Transfer'
        ];

        // User2 tries to transfer from User1's wallet
        $response = $this->actingAs($user2)->postJson('/api/financial/transactions/transfer', $payload);

        $response->assertStatus(403);
    }
}
