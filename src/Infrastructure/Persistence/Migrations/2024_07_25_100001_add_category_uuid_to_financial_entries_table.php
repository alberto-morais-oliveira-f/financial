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
        $entriesTable = config('financial.table_prefix', 'fin_') . 'entries';
        // CORREÇÃO: Usar o prefixo para a tabela de categorias
        $categoriesTable = config('financial.table_prefix', 'fin_') . 'categories';

        Schema::table($entriesTable, function (Blueprint $table) use ($categoriesTable) {
            $table->foreignUuid('category_uuid')->nullable()->after('wallet_id');

            $table->foreign('category_uuid')
                  ->references('uuid')
                  ->on($categoriesTable)
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $entriesTable = config('financial.table_prefix', 'fin_') . 'entries';

        Schema::table($entriesTable, function (Blueprint $table) {
            $table->dropForeign(['category_uuid']);
            $table->dropColumn('category_uuid');
        });
    }
};
