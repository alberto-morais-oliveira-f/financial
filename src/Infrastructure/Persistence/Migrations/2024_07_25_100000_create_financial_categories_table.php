<?php

use Am2tec\Financial\Domain\Enums\CategoryType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(config('financial.table_prefix', 'fin_') . 'categories', function (Blueprint $table) {
            // O UUID é a chave primária para garantir que seja único em sistemas distribuídos.
            $table->uuid('uuid')->primary();

            // Chave estrangeira para a própria tabela, permitindo uma estrutura de árvore (pai/filho).
            // É nullable para permitir categorias raiz (sem pai).
            $table->foreignUuid('parent_uuid')->nullable()->constrained(config('financial.table_prefix', 'fin_') . 'categories', 'uuid')->nullOnDelete();

            // Nome legível da categoria. Ex: "Despesas com Marketing".
            $table->string('name');

            // Código curto e único para referência rápida em relatórios ou integrações. Ex: "MKT-001".
            $table->string('code')->unique();

            // O tipo contábil fundamental da categoria, usando o Enum para consistência.
            // Essencial para agrupar valores em relatórios como DRE e DFC.
            $table->string('type');

            // Flag para indicar se a categoria está ativa e pode ser usada em novos lançamentos.
            $table->boolean('is_active')->default(true);
            
            // Flag para proteger categorias essenciais do sistema contra exclusão acidental.
            $table->boolean('is_system_category')->default(false);

            // Descrição opcional para fornecer mais contexto sobre o propósito da categoria.
            $table->text('description')->nullable();

            $table->timestamps();

            // Índices para otimizar consultas comuns.
            $table->index('type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('financial.table_prefix', 'fin_') . 'categories');
    }
};
