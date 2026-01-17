<?php

namespace Am2tec\Financial\Tests\Feature;

use Am2tec\Financial\Domain\Enums\PaymentStatus;
use Am2tec\Financial\Infrastructure\Persistence\Models\PaymentModel;
use Am2tec\Financial\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Am2tec\Financial\Domain\Events\PaymentReceived;

class WebhookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_handles_a_charge_succeeded_webhook()
    {
        // Arrange
        Event::fake();

        $gateway = 'stripe';
        $gatewayTransactionId = 'ch_123456789';

        // Usando create() diretamente para eliminar a factory como variável
        $payment = PaymentModel::create([
            'gateway' => $gateway,
            'gateway_transaction_id' => $gatewayTransactionId,
            'status' => PaymentStatus::PENDING,
            'amount' => 1000, // Adicionando campos obrigatórios que a factory poderia estar preenchendo
            'currency' => 'BRL',
        ]);

        $payload = [
            'type' => 'charge.succeeded',
            'data' => [
                'object' => [
                    'id' => $gatewayTransactionId,
                ],
            ],
        ];

        // Act
        $response = $this->postJson("/api/financial/webhooks/{$gateway}", $payload);

        // Assert
        $response->assertStatus(200)
                 ->assertJson(['status' => 'received']);

        $this->assertDatabaseHas('fin_payments', [
            'id' => $payment->id,
            'status' => PaymentStatus::PAID->value,
        ]);

        Event::assertDispatched(PaymentReceived::class, function ($event) use ($payment) {
            return $event->payment->uuid === $payment->id;
        });
    }
}
