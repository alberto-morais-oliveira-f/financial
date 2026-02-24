<?php

use Illuminate\Support\Facades\Route;
use Am2tec\Financial\Infrastructure\Http\Controllers\Web\WalletController;
use Am2tec\Financial\Infrastructure\Http\Controllers\Web\TransactionController;
use Am2tec\Financial\Infrastructure\Http\Controllers\Web\PaymentController;
use Am2tec\Financial\Infrastructure\Http\Controllers\Web\CategoryController;
use Am2tec\Financial\Infrastructure\Http\Controllers\Web\ReportController;
use Am2tec\Financial\Infrastructure\Http\Controllers\Web\IncomeController;
use Am2tec\Financial\Infrastructure\Http\Controllers\Web\ExpenseController;

Route::group(['prefix' => config('financial.web.prefix', 'financial'), 'as' => 'financial.', 'middleware' => config('financial.web.middleware', ['web'])], function () {
    
    Route::get('/', function() {
        return view('financial::home');
    })->name('home');

    // Wallets
    Route::resource('wallets', WalletController::class);

    // Transactions
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');

    // Payments
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/{id}/refund', [PaymentController::class, 'refundForm'])->name('payments.refund');
    Route::post('payments/{id}/refund', [PaymentController::class, 'refund'])->name('payments.refund.store');

    // Incomes
    Route::get('incomes', [IncomeController::class, 'index'])->name('incomes.index');

    // Expenses
    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Reports
    Route::get('reports/dre', [ReportController::class, 'dre'])->name('reports.dre');
});
