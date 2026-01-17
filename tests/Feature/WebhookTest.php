<?php

namespace Am2tec\Financial\Tests\Feature;

use Am2tec\Financial\Domain\Enums\PaymentStatus;
use Am2tec\Financial\Infrastructure\Persistence\Models\Payment;
use Am2tec\Financial\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Am2tec\Financial\Domain\Events\PaymentReceived;

class WebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate', ['--database' => 'testing'])->run();
    }

    /** @test */
    public function it_handles_a_charge_succeeded_webhook()
    {
        // Arrange
        Event::fake(); // Impede que os eventos sejam disparados de verdade

        $gateway = 'stripe';
        $gatewayTransactionId = 'ch_123456789';

        // Crie um pagamento com status PENDING
        $payment = Payment::factory()->create([
            'gateway' => $gateway,
            'gateway_transaction_id' => $gatewayTransactionId,
            'status' => PaymentStatus::PENDING,
        ]);

        // Payload do webhook simulado
        $payload = [
            'type' => 'charge.succeeded',
            'data' => [
                'object' => [
                    'id' => $gatewayTransactionId,
                    // ... outros dados do payload do Stripe
                ],
            ],
        ];

        // Act
        $response = $this->postJson("/api/financial/webhooks/{$gateway}", $payload);

        // Assert
        $response->assertStatus(200)
                 ->assertJson(['status' => 'received']);

        // Verifique se o pagamento foi atualizado no banco de dados
        $this->assertDatabaseHas('fin_payments', [
            'id' => $payment->id,
            'status' => PaymentStatus::PAID->value,
        ]);

        // Verifique se o evento PaymentReceived foi disparado
        Event::assertDispatched(PaymentReceived::class, function ($event) use ($payment) {
            return $event->payment->uuid === $payment->uuid;
        });
    }
}
