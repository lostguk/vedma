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
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('price_without_discount', 10, 2)->nullable()->after('price');
        });

        $items = DB::table('order_items')->get(['id', 'product_id', 'price']);
        foreach ($items as $item) {
            $productPrice = DB::table('products')->where('id', $item->product_id)->value('price');
            $paidPrice = (int) round((float) $item->price);
            $catalogPrice = $productPrice !== null ? (int) round((float) $productPrice) : $paidPrice;

            DB::table('order_items')->where('id', $item->id)->update([
                'price_without_discount' => max($paidPrice, $catalogPrice),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('price_without_discount');
        });
    }
};
