<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->string('name');
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['product_id', 'is_active']);
            $table->index('rating');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};