<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->timestamp('stock_updated_at')->nullable()->after('stock');
        });

        // isi data lama dengan updated_at agar tidak kosong
        DB::table('product_variants')->update(['stock_updated_at' => DB::raw('updated_at')]);
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('stock_updated_at');
        });
    }
};