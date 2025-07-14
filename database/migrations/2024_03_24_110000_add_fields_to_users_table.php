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
        Schema::table('users', function (Blueprint $table) {
            // Разбиваем ФИО на отдельные поля
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name');

            // Необязательные поля
            $table->string('phone')->nullable();
            $table->string('address')->nullable();

            // Поле для верификации email
            $table->string('email_verification_token')->nullable();

            // Удаляем поле name, так как теперь используем отдельные поля для ФИО
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name');

            $table->dropColumn([
                'last_name',
                'first_name',
                'phone',
                'middle_name',
                'address',
                'email_verification_token',
            ]);
        });
    }
};
