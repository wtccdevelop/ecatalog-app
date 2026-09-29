<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_hash', 64)->unique();   // HMAC-SHA256, bukan IP asli
            $table->date('first_seen_date');
            $table->date('last_seen_date');
            $table->unsignedInteger('total_visit_days')->default(1);
            $table->timestamps();

            $table->index('first_seen_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};