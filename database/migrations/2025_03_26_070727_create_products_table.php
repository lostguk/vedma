<?php

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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('old_price', 10, 2)->nullable();
            $table->decimal('weight', 8, 3)->comment('Вес в граммах');
            $table->decimal('width', 8, 2)->nullable()->comment('Ширина в см');
            $table->decimal('height', 8, 2)->nullable()->comment('Высота в см');
            $table->decimal('length', 8, 2)->nullable()->comment('Длина в см');
            $table->boolean('is_new')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Индексы для оптимизации запросов
            $table->index('name');
            $table->index(['is_new', 'is_bestseller']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
