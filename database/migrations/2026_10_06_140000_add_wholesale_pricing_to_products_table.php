<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('wholesale_min_qty')->nullable()->after('old_price');
            $table->decimal('wholesale_price', 10, 2)->nullable()->after('wholesale_min_qty');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['wholesale_min_qty', 'wholesale_price']);
        });
    }
};
