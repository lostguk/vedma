<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('total_price_without_discount')->nullable()->after('total_price');
            $table->unsignedInteger('total_price_with_discount')->nullable()->after('total_price_without_discount');
        });

        DB::table('orders')->update([
            'total_price_without_discount' => DB::raw('ROUND(total_price)'),
            'total_price_with_discount' => DB::raw('ROUND(total_price)'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['total_price_without_discount', 'total_price_with_discount']);
        });
    }
};
