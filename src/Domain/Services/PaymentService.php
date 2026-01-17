<?php

namespace Am2tec\Financial\Domain\Services;

use Illuminate\Support\Str;
use Am2tec\Financial\Application\DTOs\PaymentData;
use Am2tec\Financial\Domain\Contracts\PaymentGatewayAdapter;
use Am2tec\Financial\Domain\Contracts\PaymentRepositoryInterface;
use Am2tec\Financial\Domain\Contracts\RefundRepositoryInterface;
use Am2tec\Financial\Domain\Entities\Payment;
use Am2tec\Financial\Domain\Entities\Refund;
use Am2tec\Financial\Domain\Enums\PaymentStatus;
use Am2tec\Financial\Domain\Enums\RefundStatus;
use Am2tec\Financial\Domain\Events\PaymentReceived;
use Am2tec\Financial\Domain\Events\PaymentRefunded;
use Am2tec\Financial\Domain\ValueObjects\Money;

class PaymentService
{
    public function __construct(
        protected PaymentRepositoryInterface $paymentRepository,
        protected RefundRepositoryInterface $refundRepository,
        protected PaymentGatewayAdapter $gatewayAdapter
    ) {}

    public function charge(PaymentData $data): Payment
    {
        $payment = new Payment(
            uuid: Str::uuid()->toString(),
            gateway: 'default',
            gatewayTransactionId: null,
            amount: $data->amount,
            status: PaymentStatus::PENDING
        );
        
        $this->paymentRepository->save($payment);

        $response = $this->gatewayAdapter->charge($data);

        $updatedPayment = new Payment(
            uuid: $payment->uuid,
            gateway: $payment->gateway,
            gatewayTransactionId: $response->gatewayTransactionId,
            amount: $payment->amount,
            status: $response->status,
            createdAt: $payment->createdAt,
            errorMessage: $response->errorMessage
        );

        $finalPayment = $this->paymentRepository->save($updatedPayment);

        if ($finalPayment->status === PaymentStatus::PAID) {
            PaymentReceived::dispatch($finalPayment);
        }

        return $finalPayment;
    }

    public function refund(string $paymentId, Money $amount, ?string $reason = null): Refund
    {
        $payment = $this->paymentRepository->findById($paymentId);

        if (!$payment) {
            throw new \InvalidArgumentException("Payment not found.");
        }

        if (!in_array($payment->status, [PaymentStatus::PAID, PaymentStatus::PARTIALLY_REFUNDED])) {
            throw new \DomainException("Only paid or partially refunded payments can be refunded.");
        }

        $totalRefunded = $this->refundRepository->getTotalRefundedForPayment($paymentId);
        if (($totalRefunded + $amount->amount) > $payment->amount->amount) {
            throw new \DomainException("Refund amount exceeds the payable amount.");
        }

        $refund = new Refund(
            uuid: Str::uuid()->toString(),
            paymentId: $paymentId,
            amount: $amount,
            reason: $reason
        );
        $this->refundRepository->save($refund);

        // This part would be inside a try-catch block in a real scenario
        $gatewayResponse = $this->gatewayAdapter->refund($payment->gatewayTransactionId, $amount);

        if ($gatewayResponse->status === PaymentStatus::REFUNDED) { // Assuming gateway returns a payment status
            $refund->markAsProcessed($gatewayResponse->gatewayTransactionId);
            $this->refundRepository->save($refund);

            $newTotalRefunded = $totalRefunded + $amount->amount;
            $newPaymentStatus = $newTotalRefunded === $payment->amount->amount
                ? PaymentStatus::REFUNDED
                : PaymentStatus::PARTIALLY_REFUNDED;

            $updatedPayment = new Payment(
                uuid: $payment->uuid,
                gateway: $payment->gateway,
                gatewayTransactionId: $payment->gatewayTransactionId,
                amount: $payment->amount,
                status: $newPaymentStatus,
                createdAt: $payment->createdAt
            );
            $this->paymentRepository->save($updatedPayment);

            PaymentRefunded::dispatch($updatedPayment); // Dispatch with the updated payment
        } else {
            $refund->markAsFailed($gatewayResponse->errorMessage);
            $this->refundRepository->save($refund);
            throw new \DomainException("Gateway failed to process refund: " . $gatewayResponse->errorMessage);
        }

        return $refund;
    }
}
