<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');

        Schema::table($prefix . 'wallets', function (Blueprint $table) {
            $table->string('type')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');

        Schema::table($prefix . 'wallets', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
