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

    public function handle(string $gateway, array $payload): void
    {
        $eventType = $payload['type'] ?? null;
        $data = $payload['data']['object'] ?? [];

        switch ($eventType) {
            case 'charge.succeeded':
                $this->handleChargeSucceeded($gateway, $data);
                break;
        }
    }

    protected function handleChargeSucceeded(string $gateway, array $data): void
    {
        $gatewayTransactionId = $data['id'] ?? null;
        if (!$gatewayTransactionId) {
            return;
        }

        $payment = $this->paymentRepository->findByGatewayId($gateway, $gatewayTransactionId);

        if ($payment && $payment->status->value === PaymentStatus::PENDING->value) {
            $payment->status = PaymentStatus::PAID;
            
            $updatedPayment = $this->paymentRepository->save($payment);

            PaymentReceived::dispatch($updatedPayment);
        }
    }
}
