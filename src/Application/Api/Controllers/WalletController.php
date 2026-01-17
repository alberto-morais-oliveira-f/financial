<?php

namespace Am2tec\Financial\Application\Api\Controllers;

use Am2tec\Financial\Application\Api\Resources\WalletResource;
use Am2tec\Financial\Domain\Exceptions\WalletNotFoundException;
use Am2tec\Financial\Domain\Services\WalletService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WalletController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected WalletService $walletService)
    {
    }

    public function show(Request $request, string $id)
    {
        try {
            $wallet = $this->walletService->findById($id);
            $this->authorize('view', $wallet);

            return WalletResource::fromEntity($wallet);
        } catch (WalletNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
