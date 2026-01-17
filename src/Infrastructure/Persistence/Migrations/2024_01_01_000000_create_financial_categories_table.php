<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_categories', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->foreignUuid('parent_uuid')->nullable()->constrained('financial_categories', 'uuid');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type');
            $table->boolean('is_system_category')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_categories');
    }
};
