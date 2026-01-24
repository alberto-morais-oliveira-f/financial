<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');

        $tableName = $prefix . '_categories';

        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->uuid('uuid')->primary();
                $table->uuid('parent_uuid')->nullable()->comment('UUID da categoria pai, para hierarquia.');
                $table->string('name')->comment('Nome da categoria.');
                $table->string('slug')->unique()->comment('Slug único para a categoria.');
                $table->string('type')->comment('Tipo da categoria (revenue, expense, cost, etc.).');
                $table->boolean('is_active')->default(true)->comment('Indica se a categoria está ativa.');
                $table->boolean('is_system_category')->default(false)->comment('Indica se é uma categoria do sistema e não pode ser alterada pelo usuário.');
                $table->text('description')->nullable()->comment('Descrição da categoria.');
                $table->timestamps();
            });

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->foreign('parent_uuid')->references('uuid')->on($tableName)->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');

        $tableName = $prefix . '_categories';
        Schema::dropIfExists($tableName);
    }
};
