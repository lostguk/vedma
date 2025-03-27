<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     type="object",
 *     required={"first_name", "last_name", "middle_name", "email", "password", "password_confirmation"},
 *
 *     @OA\Property(property="first_name", type="string", example="Иван", description="Имя"),
 *     @OA\Property(property="last_name", type="string", example="Иванов", description="Фамилия"),
 *     @OA\Property(property="middle_name", type="string", example="Иванович", description="Отчество"),
 *     @OA\Property(property="email", type="string", format="email", example="user@example.com", description="Email"),
 *     @OA\Property(property="password", type="string", format="password", example="password123", description="Пароль"),
 *     @OA\Property(property="password_confirmation", type="string", format="password", example="password123", description="Подтверждение пароля"),
 *     @OA\Property(property="phone", type="string", example="+7 999 123 45 67", description="Телефон"),
 *     @OA\Property(property="country", type="string", example="Россия", description="Страна"),
 *     @OA\Property(property="region", type="string", example="Московская область", description="Регион"),
 *     @OA\Property(property="city", type="string", example="Москва", description="Город"),
 *     @OA\Property(property="postal_code", type="string", example="123456", description="Почтовый индекс"),
 *     @OA\Property(property="address", type="string", example="ул. Пушкина, д. 1", description="Адрес")
 * )
 */
class Auth {}
