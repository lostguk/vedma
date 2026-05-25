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
        DB::table('orders')->update([
            'total_price' => DB::raw('GREATEST(0, ROUND(total_price))'),
            'delivery_price' => DB::raw('GREATEST(0, ROUND(delivery_price))'),
        ]);

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('total_price')->change();
            $table->unsignedInteger('delivery_price')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_price', 10, 2)->change();
            $table->decimal('delivery_price', 10, 2)->nullable()->change();
        });
    }
};
