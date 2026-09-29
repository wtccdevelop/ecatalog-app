<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('brand_id')
                ->constrained('brands')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image_path')->nullable();
            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['brand_id', 'is_active']);
            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};