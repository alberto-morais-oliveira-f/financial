<?php

namespace Am2tec\Financial\Application\Api\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Am2tec\Financial\Application\Api\Requests\StoreRefundRequest;
use Am2tec\Financial\Application\Api\Resources\RefundResource;
use Am2tec\Financial\Domain\Services\PaymentService;
use Am2tec\Financial\Domain\ValueObjects\Currency;
use Am2tec\Financial\Domain\ValueObjects\Money;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected PaymentService $paymentService
    ) {}

    public function refund(StoreRefundRequest $request, string $id)
    {
        try {
            $money = new Money($request->amount, new Currency($request->currency));

            $refund = $this->paymentService->refund($id, $money, $request->reason);

            return RefundResource::fromEntity($refund);

        } catch (\DomainException | \InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
