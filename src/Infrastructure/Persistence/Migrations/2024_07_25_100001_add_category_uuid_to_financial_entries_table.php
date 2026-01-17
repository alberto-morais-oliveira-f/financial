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
        $categoriesTable = 'financial_categories';

        Schema::table($entriesTable, function (Blueprint $table) use ($categoriesTable) {
            // Adiciona a coluna para a chave estrangeira da categoria.
            // É nullable porque uma categoria pode não ser aplicável a todos os tipos de lançamentos,
            // ou pode ser definida posteriormente em um estado de rascunho (DRAFT).
            $table->foreignUuid('category_uuid')->nullable()->after('wallet_id');

            // Define a restrição de chave estrangeira.
            // `nullOnDelete` garante que, se uma categoria for excluída (o que deve ser raro e controlado),
            // os lançamentos históricos não sejam perdidos, apenas percam a referência à categoria.
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
            // Remove a restrição e a coluna na ordem inversa da criação.
            $table->dropForeign(['category_uuid']);
            $table->dropColumn('category_uuid');
        });
    }
};
