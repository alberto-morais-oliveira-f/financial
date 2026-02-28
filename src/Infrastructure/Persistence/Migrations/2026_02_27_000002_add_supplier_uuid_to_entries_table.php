<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');
        $entriesTable = $prefix . 'entries';
        $suppliersTable = $prefix . 'suppliers';

        if (Schema::hasTable($entriesTable)) {
            Schema::table($entriesTable, function (Blueprint $table) use ($suppliersTable) {
                if (!Schema::hasColumn($table->getTable(), 'supplier_uuid')) {
                    $table->uuid('supplier_uuid')->nullable()->after('category_uuid');
                    $table->foreign('supplier_uuid')->references('uuid')->on($suppliersTable)->nullOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');
        $entriesTable = $prefix . 'entries';

        if (Schema::hasTable($entriesTable)) {
            Schema::table($entriesTable, function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'supplier_uuid')) {
                    $table->dropForeign(['supplier_uuid']);
                    $table->dropColumn('supplier_uuid');
                }
            });
        }
    }
};
