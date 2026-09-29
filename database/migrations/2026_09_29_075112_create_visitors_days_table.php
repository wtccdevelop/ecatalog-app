<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_days', function (Blueprint $table) {
            $table->id();

            $table->foreignId('visitor_id')->constrained('visitors')->cascadeOnDelete();
            $table->date('visit_date');

            // Perangkat (dari User-Agent)
            $table->string('device_type', 20)->nullable();   // smartphone | tablet | desktop
            $table->string('device_brand', 50)->nullable();  // Samsung, Oppo, Apple...
            $table->string('device_model', 100)->nullable();
            $table->string('os', 50)->nullable();
            $table->string('browser', 50)->nullable();

            // Wilayah (opsional, dari GeoIP)
            $table->char('country_code', 2)->nullable();
            $table->string('region', 100)->nullable();       // provinsi
            $table->string('city', 100)->nullable();

            // Sumber trafik (referrer pertama di hari itu)
            $table->string('referrer_source', 30)->nullable(); // instagram|tiktok|facebook|whatsapp|google|direct|other
            $table->string('referrer_host')->nullable();

            $table->unsignedInteger('page_views_count')->default(0);
            $table->timestamp('first_visit_at')->nullable();
            $table->timestamp('last_visit_at')->nullable();

            $table->timestamps();

            // Kunci "1 IP = 1 pengunjung per hari"
            $table->unique(['visitor_id', 'visit_date']);
            $table->index('visit_date');
            $table->index(['visit_date', 'device_brand']);
            $table->index(['visit_date', 'referrer_source']);
            $table->index(['visit_date', 'region']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_days');
    }
};