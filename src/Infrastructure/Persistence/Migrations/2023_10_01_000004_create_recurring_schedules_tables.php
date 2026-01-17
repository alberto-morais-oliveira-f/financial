<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');

        Schema::create($prefix . 'recurring_schedules', function (Blueprint $table) use ($prefix) {
            $table->uuid('id')->primary();
            $table->uuid('wallet_id');
            $table->string('currency', 3);
            $table->bigInteger('amount');
            $table->string('cron_expression');
            $table->string('description');
            $table->timestamp('next_run_at');
            $table->timestamp('ends_at')->nullable();
            $table->string('status')->default('active');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('wallet_id')->references('id')->on($prefix . 'wallets')->cascadeOnDelete();
            $table->index(['status', 'next_run_at']);
        });
    }

    public function down(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');
        Schema::dropIfExists($prefix . 'recurring_schedules');
    }
};
