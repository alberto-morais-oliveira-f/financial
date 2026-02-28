<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');

        Schema::table($prefix . 'titles', function (Blueprint $table) use ($prefix) {
            $table->uuid('supplier_uuid')->nullable()->after('wallet_id');
            $table->foreign('supplier_uuid')->references('uuid')->on($prefix . 'suppliers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');

        Schema::table($prefix . 'titles', function (Blueprint $table) {
            $table->dropForeign(['supplier_uuid']);
            $table->dropColumn('supplier_uuid');
        });
    }
};
