<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // CORREÇÃO: Usar o prefixo configurável para criar a tabela
        Schema::create(config('financial.table_prefix', 'fin_') . 'categories', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('parent_uuid')->nullable()->comment('UUID da categoria pai, para hierarquia.');
            $table->string('name')->comment('Nome da categoria.');
            $table->string('code')->unique()->comment('Código único para a categoria.');
            $table->string('type')->comment('Tipo da categoria (revenue, expense, cost, etc.).');
            $table->boolean('is_active')->default(true)->comment('Indica se a categoria está ativa.');
            $table->boolean('is_system_category')->default(false)->comment('Indica se é uma categoria do sistema e não pode ser alterada pelo usuário.');
            $table->text('description')->nullable()->comment('Descrição da categoria.');
            $table->timestamps();

            $table->foreign('parent_uuid')->references('uuid')->on(config('financial.table_prefix', 'fin_') . 'categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('financial.table_prefix', 'fin_') . 'categories');
    }
};
