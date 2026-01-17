<?php

namespace Am2tec\Financial\Domain\Services;

use Am2tec\Financial\Domain\Contracts\PaymentRepositoryInterface;
use Am2tec\Financial\Domain\Enums\PaymentStatus;
use Am2tec\Financial\Domain\Events\PaymentReceived;
use Am2tec\Financial\Domain\Entities\Payment;

class WebhookService
{
    public function __construct(protected PaymentRepositoryInterface $paymentRepository)
    {
    }

    /**
     * Process an incoming webhook from a payment gateway.
     *
     * @param string $gateway
     * @param array $payload
     * @return void
     */
    public function handle(string $gateway, array $payload): void
    {
        // In a real application, you would have different strategies or classes
        // for each gateway to handle their specific payload structures.
        // For this example, we use a simple switch.

        $eventType = $payload['type'] ?? null;
        $data = $payload['data']['object'] ?? [];

        switch ($eventType) {
            case 'charge.succeeded':
                $this->handleChargeSucceeded($gateway, $data);
                break;

            // Handle other events like 'charge.refunded', 'charge.failed', etc.
        }
    }

    /**
     * Handle the logic for a successful charge.
     *
     * @param string $gateway
     * @param array $data
     * @return void
     */
    protected function handleChargeSucceeded(string $gateway, array $data): void
    {
        $gatewayTransactionId = $data['id'] ?? null;
        if (!$gatewayTransactionId) {
            return;
        }

        $payment = $this->paymentRepository->findByGatewayId($gateway, $gatewayTransactionId);

        if ($payment && $payment->status === PaymentStatus::PENDING) {
            // Create a new entity for the update to enforce domain rules if any
            $updatedPayment = new Payment(
                uuid: $payment->uuid,
                gateway: $payment->gateway,
                gatewayTransactionId: $payment->gatewayTransactionId,
                amount: $payment->amount,
                status: PaymentStatus::PAID,
                createdAt: $payment->createdAt
            );
            $this->paymentRepository->save($updatedPayment);
            PaymentReceived::dispatch($updatedPayment);
        }
    }
}
