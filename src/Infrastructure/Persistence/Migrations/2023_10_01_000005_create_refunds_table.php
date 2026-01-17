<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');

        Schema::create($prefix . 'refunds', function (Blueprint $table) use ($prefix) {
            $table->uuid('id')->primary();
            $table->uuid('payment_id');
            $table->string('currency', 3);
            $table->bigInteger('amount');
            $table->string('status')->default('pending');
            $table->string('gateway_refund_id')->nullable()->index();
            $table->string('reason')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();

            $table->foreign('payment_id')->references('id')->on($prefix . 'payments')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');
        Schema::dropIfExists($prefix . 'refunds');
    }
};
