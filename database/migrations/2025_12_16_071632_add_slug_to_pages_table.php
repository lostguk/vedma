<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Проверяем, существует ли колонка slug
        if (! Schema::hasColumn('pages', 'slug')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('title');
            });
        }

        // Заполняем slug для существующих записей (где slug пустой или null)
        $pages = DB::table('pages')
            ->whereNull('slug')
            ->orWhere('slug', '')
            ->get();

        foreach ($pages as $page) {
            $slug = Str::slug($page->title);
            $originalSlug = $slug;
            $counter = 1;

            // Проверяем уникальность slug
            while (DB::table('pages')
                ->where('slug', $slug)
                ->where('id', '!=', $page->id)
                ->exists()) {
                $slug = $originalSlug.'-'.$counter;
                $counter++;
            }

            DB::table('pages')
                ->where('id', $page->id)
                ->update(['slug' => $slug]);
        }

        // Добавляем уникальный индекс, если его еще нет
        Schema::table('pages', function (Blueprint $table) {
            if (! $this->hasUniqueIndex('pages', 'slug')) {
                $table->unique('slug');
            }
            // Делаем поле обязательным
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Проверяет наличие уникального индекса на колонке.
     */
    private function hasUniqueIndex(string $table, string $column): bool
    {
        $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Column_name = ? AND Non_unique = 0", [$column]);

        return count($indexes) > 0;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
