<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('category_home_page_content', 'sort_order')) {
            Schema::table('category_home_page_content', function (Blueprint $table): void {
                $table->dropColumn('sort_order');
            });
        }

        if (Schema::hasColumn('categories', 'sort_order')) {
            Schema::table('categories', function (Blueprint $table): void {
                $table->dropColumn('sort_order');
            });
        }

        if (Schema::hasColumn('products', 'sort_order')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->dropColumn('sort_order');
            });
        }

        if (Schema::hasColumn('hero_slides', 'sort_order')) {
            Schema::table('hero_slides', function (Blueprint $table): void {
                $table->dropColumn('sort_order');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('categories', 'sort_order')) {
            Schema::table('categories', function (Blueprint $table): void {
                $table->integer('sort_order')->default(0)->after('parent_id');
            });
        }

        if (! Schema::hasColumn('products', 'sort_order')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->integer('sort_order')->default(0)->after('is_bestseller');
                $table->index('sort_order');
            });
        }

        if (! Schema::hasColumn('hero_slides', 'sort_order')) {
            Schema::table('hero_slides', function (Blueprint $table): void {
                $table->integer('sort_order')->default(0)->after('image_path');
            });
        }

        if (! Schema::hasColumn('category_home_page_content', 'sort_order')) {
            Schema::table('category_home_page_content', function (Blueprint $table): void {
                $table->integer('sort_order')->default(0)->after('home_page_content_id');
            });
        }
    }
};
