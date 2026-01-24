<?php

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
        // Usar o prefixo configurável para manter consistência com as tabelas existentes
        $prefix = config('financial.table_prefix', 'fin_');
        $entriesTable = $prefix . 'entries';
        
        // A tabela de categorias foi fixada como 'financial_categories' na migração anterior
        $categoriesTable = 'fin_categories';

        if (Schema::hasTable($entriesTable)) {
            Schema::table($entriesTable, function (Blueprint $table) use ($categoriesTable) {
                // Adiciona a coluna apenas se ela não existir
                if (!Schema::hasColumn($table->getTable(), 'category_uuid')) {
                    // Usa 'wallet_id' como referência de posição, pois é o nome da coluna na migração original
                    $table->uuid('category_uuid')->nullable()->after('wallet_id');
                }
            });

            Schema::table($entriesTable, function (Blueprint $table) use ($categoriesTable) {
                 $table->foreign('category_uuid')
                      ->references('uuid')
                      ->on($categoriesTable)
                      ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');
        $entriesTable = $prefix . 'entries';

        if (Schema::hasTable($entriesTable)) {
            Schema::table($entriesTable, function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'category_uuid')) {
                    $table->dropForeign(['category_uuid']);
                    $table->dropColumn('category_uuid');
                }
            });
        }
    }
};
