<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');

        Schema::table($prefix . 'entries', function (Blueprint $table) {
            $table->string('description')->nullable()->after('type');
        });
    }

    public function down(): void
    {
        $prefix = config('financial.table_prefix', 'fin_');
        
        Schema::table($prefix . 'entries', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
