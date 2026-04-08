<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('categories', 'meta_title')) {
            Schema::table('categories', function (Blueprint $table): void {
                $table->dropColumn('meta_title');
            });
        }

        if (Schema::hasColumn('categories', 'meta_description')) {
            Schema::table('categories', function (Blueprint $table): void {
                $table->dropColumn('meta_description');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('categories', 'meta_title')) {
            Schema::table('categories', function (Blueprint $table): void {
                $table->string('meta_title')->nullable()->after('exclude_from_shipping');
            });
        }

        if (! Schema::hasColumn('categories', 'meta_description')) {
            Schema::table('categories', function (Blueprint $table): void {
                $table->text('meta_description')->nullable()->after('meta_title');
            });
        }
    }
};
