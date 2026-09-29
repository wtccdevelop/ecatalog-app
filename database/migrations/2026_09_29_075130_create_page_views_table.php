<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visitor_day_id')->constrained('visitor_days')->cascadeOnDelete();

            $table->string('event', 20)->default('pageview'); // pageview | wa_click | search | installment_view
            $table->string('path');
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('search_keyword')->nullable();

            // Disimpan terpisah agar query jam ramai cepat (zona waktu app: Asia/Jakarta)
            $table->date('visit_date');
            $table->unsignedTinyInteger('visit_hour');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['visit_date', 'visit_hour']);            // grafik / heatmap jam ramai
            $table->index(['event', 'visit_date']);                 // klik WA & pencarian per hari
            $table->index(['product_id', 'event', 'visit_date']);   // produk populer & klik WA per produk
            $table->index('created_at');                            // online sekarang (5 menit terakhir)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};