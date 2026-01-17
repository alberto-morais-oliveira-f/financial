<?php

namespace Am2tec\Financial\Application\Api\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Am2tec\Financial\Application\Api\Requests\StoreTransferRequest;
use Am2tec\Financial\Application\Api\Resources\TransactionResource;
use Am2tec\Financial\Domain\Contracts\CurrencyResolver;
use Am2tec\Financial\Domain\Entities\Transaction;
use Am2tec\Financial\Domain\Services\TransactionService;
use Am2tec\Financial\Domain\ValueObjects\Money;

class TransactionController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected TransactionService $transactionService,
        protected CurrencyResolver $currencyResolver
    ) {}

    public function store(StoreTransferRequest $request)
    {
        $this->authorize('create', [Transaction::class, $request->from_wallet_id]);

        try {
            $currency = $this->currencyResolver->resolve();
            $money = new Money($request->amount, $currency);

            $transaction = $this->transactionService->transfer(
                $request->from_wallet_id,
                $request->to_wallet_id,
                $money,
                $request->description
            );

            return TransactionResource::fromEntity($transaction);

        } catch (\DomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
