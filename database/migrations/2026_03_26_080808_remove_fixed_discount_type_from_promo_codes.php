<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('promo_codes')
            ->where('discount_type', 'fixed')
            ->update(['discount_type' => 'percent']);

        DB::statement("ALTER TABLE promo_codes MODIFY discount_type ENUM('percent') NOT NULL DEFAULT 'percent'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE promo_codes MODIFY discount_type ENUM('percent', 'fixed') NOT NULL");
    }
};
