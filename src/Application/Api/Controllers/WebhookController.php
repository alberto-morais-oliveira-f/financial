<?php

namespace Am2tec\Financial\Application\Api\Controllers;

use Am2tec\Financial\Domain\Services\WebhookService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WebhookController extends Controller
{
    public function __construct(protected WebhookService $webhookService)
    {
    }

    /**
     * Handle incoming webhooks from a payment gateway.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $gateway
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, string $gateway)
    {
        // In a real application, you would validate the webhook signature here.
        // This responsibility could also be in a middleware.

        $this->webhookService->handle($gateway, $request->all());

        return response()->json(['status' => 'received']);
    }
}
