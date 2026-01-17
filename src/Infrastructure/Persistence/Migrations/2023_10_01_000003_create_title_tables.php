<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');

        Schema::create($prefix . 'titles', function (Blueprint $table) use ($prefix) {
            $table->uuid('id')->primary();
            $table->uuid('wallet_id');
            $table->string('type'); // receivable, payable
            $table->string('currency', 3);
            $table->bigInteger('amount');
            $table->date('due_date');
            $table->string('description');
            $table->string('status')->default('pending');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('wallet_id')->references('id')->on($prefix . 'wallets')->cascadeOnDelete();
            $table->index(['wallet_id', 'status', 'due_date']);
        });
    }

    public function down(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');
        Schema::dropIfExists($prefix . 'titles');
    }
};
