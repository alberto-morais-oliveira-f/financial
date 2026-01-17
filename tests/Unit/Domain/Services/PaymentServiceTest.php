<?php

namespace Am2tec\Financial\Tests\Unit\Domain\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Am2tec\Financial\Application\DTOs\GatewayResponse;
use Am2tec\Financial\Application\DTOs\PaymentData;
use Am2tec\Financial\Domain\Contracts\PaymentGatewayAdapter;
use Am2tec\Financial\Domain\Entities\Payment;
use Am2tec\Financial\Domain\Enums\PaymentStatus;
use Am2tec\Financial\Domain\Enums\RefundStatus;
use Am2tec\Financial\Domain\Events\PaymentRefunded;
use Am2tec\Financial\Domain\Services\PaymentService;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;
use Am2tec\Financial\Infrastructure\Persistence\Models\PaymentModel;
use Am2tec\Financial\Tests\TestCase;
use Mockery;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    private $gatewayMock;
    private PaymentService $paymentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gatewayMock = Mockery::mock(PaymentGatewayAdapter::class);
        $this->app->instance(PaymentGatewayAdapter::class, $this->gatewayMock);
        $this->paymentService = $this->app->make(PaymentService::class);
        Event::fake();
    }

    public function test_can_process_partial_refund()
    {
        // Arrange
        $payment = PaymentModel::factory()->create([
            'amount' => 1000,
            'status' => PaymentStatus::PAID->value,
            'gateway_transaction_id' => 'ch_123',
        ]);
        $refundAmount = Money::of(400, Currency::BRL());

        $this->gatewayMock->shouldReceive('refund')
            ->once()
            ->andReturn(new GatewayResponse('re_456', PaymentStatus::REFUNDED)); // Gateway response is simplified

        // Act
        $refund = $this->paymentService->refund($payment->id, $refundAmount);

        // Assert
        $this->assertDatabaseHas('fin_refunds', [
            'id' => $refund->uuid,
            'payment_id' => $payment->id,
            'amount' => 400,
            'status' => RefundStatus::PROCESSED->value,
        ]);

        $this->assertDatabaseHas('fin_payments', [
            'id' => $payment->id,
            'status' => PaymentStatus::PARTIALLY_REFUNDED->value,
        ]);

        Event::assertDispatched(PaymentRefunded::class);
    }

    public function test_can_process_full_refund()
    {
        // Arrange
        $payment = PaymentModel::factory()->create([
            'amount' => 1000,
            'status' => PaymentStatus::PAID->value,
            'gateway_transaction_id' => 'ch_123',
        ]);
        $refundAmount = Money::of(1000, Currency::BRL());

        $this->gatewayMock->shouldReceive('refund')
            ->once()
            ->andReturn(new GatewayResponse('re_789', PaymentStatus::REFUNDED));

        // Act
        $this->paymentService->refund($payment->id, $refundAmount);

        // Assert
        $this->assertDatabaseHas('fin_payments', [
            'id' => $payment->id,
            'status' => PaymentStatus::REFUNDED->value,
        ]);
    }

    public function test_cannot_refund_more_than_payment_amount()
    {
        // Arrange
        $this->expectException(\DomainException::class);
        $payment = PaymentModel::factory()->create(['amount' => 1000, 'status' => PaymentStatus::PAID->value]);
        $refundAmount = Money::of(1001, Currency::BRL());

        // Act
        $this->paymentService->refund($payment->id, $refundAmount);
    }
}
