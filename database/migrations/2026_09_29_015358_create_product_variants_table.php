<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('ram')->nullable();
            $table->unsignedSmallInteger('storage')->nullable();
            $table->string('color')->nullable();

            // Harga disimpan dalam Rupiah tanpa decimal
            $table->unsignedBigInteger('price');

            $table->unsignedInteger('stock')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['product_id', 'is_active']);
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};