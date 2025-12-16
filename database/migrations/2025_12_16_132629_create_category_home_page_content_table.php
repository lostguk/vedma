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
        Schema::create('category_home_page_content', function (Blueprint $table): void {
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('home_page_content_id')->constrained()->cascadeOnDelete();
            $table->primary(['category_id', 'home_page_content_id']);

            // Индекс для оптимизации запросов
            $table->index(['home_page_content_id', 'category_id'], 'cat_home_page_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_home_page_content');
    }
};
