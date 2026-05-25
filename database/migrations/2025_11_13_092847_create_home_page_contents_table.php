<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_page_contents', function (Blueprint $table): void {
            $table->id();

            $table->string('hero_title');
            $table->string('hero_subtitle')->nullable();
            $table->string('hero_button_label')->nullable();
            $table->string('hero_button_url')->nullable();
            $table->string('hero_image_path')->nullable();

            $table->string('hero_feature_1_text')->nullable();
            $table->string('hero_feature_1_image_path')->nullable();

            $table->string('hero_feature_2_text')->nullable();
            $table->string('hero_feature_2_image_path')->nullable();

            $table->string('hero_feature_3_text')->nullable();
            $table->string('hero_feature_3_image_path')->nullable();

            $table->string('about_title');
            $table->text('about_description')->nullable();

            $table->string('about_trust_title')->nullable();

            $table->string('about_trust_feature_1_title')->nullable();
            $table->string('about_trust_feature_1_image_path')->nullable();

            $table->string('about_trust_feature_2_title')->nullable();
            $table->string('about_trust_feature_2_image_path')->nullable();

            $table->string('about_trust_feature_3_title')->nullable();
            $table->string('about_trust_feature_3_image_path')->nullable();

            $table->string('about_motto')->nullable();

            $table->string('about_left_image_path')->nullable();
            $table->string('about_right_image_path')->nullable();

            $table->string('stats_title')->nullable();

            $table->string('stats_item_1_value')->nullable();
            $table->string('stats_item_1_label')->nullable();
            $table->string('stats_item_1_text')->nullable();

            $table->string('stats_item_2_value')->nullable();
            $table->string('stats_item_2_label')->nullable();
            $table->string('stats_item_2_text')->nullable();

            $table->string('stats_item_3_value')->nullable();
            $table->string('stats_item_3_label')->nullable();
            $table->string('stats_item_3_text')->nullable();

            $table->string('about_more_button_label')->nullable();
            $table->string('about_more_button_url')->nullable();

            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('home_page_contents');
    }
};
