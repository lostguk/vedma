<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('category_home_page_content', function (Blueprint $table): void {
            $table->integer('sort_order')->default(0)->after('home_page_content_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_home_page_content', function (Blueprint $table): void {
            $table->dropColumn('sort_order');
        });
    }
};
