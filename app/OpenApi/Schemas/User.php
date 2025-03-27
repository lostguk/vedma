<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     type="object",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="first_name", type="string", example="Иван"),
 *     @OA\Property(property="last_name", type="string", example="Иванов"),
 *     @OA\Property(property="middle_name", type="string", example="Иванович"),
 *     @OA\Property(property="full_name", type="string", example="Иванов Иван Иванович"),
 *     @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *     @OA\Property(property="phone", type="string", example="+7 999 123 45 67"),
 *     @OA\Property(
 *         property="address",
 *         type="object",
 *         @OA\Property(property="country", type="string", example="Россия"),
 *         @OA\Property(property="region", type="string", example="Московская область"),
 *         @OA\Property(property="city", type="string", example="Москва"),
 *         @OA\Property(property="postal_code", type="string", example="123456"),
 *         @OA\Property(property="address", type="string", example="ул. Пушкина, д. 1")
 *     ),
 *     @OA\Property(property="email_verified", type="boolean", example=false),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class User {}
