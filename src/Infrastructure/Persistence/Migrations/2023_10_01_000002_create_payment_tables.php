<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');

        Schema::create($prefix . 'payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('gateway');
            $table->string('gateway_transaction_id')->nullable()->index();
            $table->string('currency', 3);
            $table->bigInteger('amount');
            $table->string('status')->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');
        Schema::dropIfExists($prefix . 'payments');
    }
};
