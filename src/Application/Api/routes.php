<?php

use Am2tec\Financial\Application\Api\Controllers\DreController;
use Illuminate\Support\Facades\Route;
use Am2tec\Financial\Application\Api\Controllers\PaymentController;
use Am2tec\Financial\Application\Api\Controllers\TransactionController;
use Am2tec\Financial\Application\Api\Controllers\WalletController;
use Am2tec\Financial\Application\Api\Controllers\WebhookController;

// Wallet Routes
Route::get('/wallets/{id}', [WalletController::class, 'show']);

// Transaction Routes
Route::post('/transactions/transfer', [TransactionController::class, 'store']);

// Payment Routes
Route::post('/payments/{id}/refund', [PaymentController::class, 'refund']);

// Webhook Routes
Route::post('/webhooks/{gateway}', [WebhookController::class, 'handle']);

// Report Routes
Route::get('/reports/dre', [DreController::class, 'show'])->name('financial.reports.dre.show');
