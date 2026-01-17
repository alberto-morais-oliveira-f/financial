<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');

        Schema::create($prefix . 'wallets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('owner_type');
            $table->string('owner_id');
            $table->string('currency', 3);
            $table->bigInteger('balance')->default(0);
            $table->string('status')->default('active');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['owner_type', 'owner_id']);
        });

        Schema::create($prefix . 'transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reference_code')->unique();
            $table->string('description');
            $table->string('status')->default('pending');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create($prefix . 'entries', function (Blueprint $table) use ($prefix) {
            $table->uuid('id')->primary();
            $table->uuid('transaction_id');
            $table->uuid('wallet_id');
            $table->string('type'); // debit or credit
            $table->bigInteger('amount');
            $table->bigInteger('before_balance')->nullable();
            $table->bigInteger('after_balance')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on($prefix . 'transactions')->cascadeOnDelete();
            $table->foreign('wallet_id')->references('id')->on($prefix . 'wallets')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');
        
        Schema::dropIfExists($prefix . 'entries');
        Schema::dropIfExists($prefix . 'transactions');
        Schema::dropIfExists($prefix . 'wallets');
    }
};
