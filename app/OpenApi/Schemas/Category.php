<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="Category",
 *     type="object",
 *     required={"id", "name", "slug"},
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Все свечи"),
 *     @OA\Property(property="slug", type="string", example="vse-svechi"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Описание категории"),
 *     @OA\Property(property="icon", type="string", nullable=true, example="http://localhost:8000/storage/1/icon.jpg"),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, example=null),
 *     @OA\Property(property="sort_order", type="integer", example=0),
 *     @OA\Property(property="is_visible", type="boolean", example=true),
 *     @OA\Property(
 *         property="children",
 *         type="array",
 *         nullable=true,
 *
 *         @OA\Items(ref="#/components/schemas/Category")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="CategoryCollection",
 *     type="object",
 *
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *
 *         @OA\Items(ref="#/components/schemas/Category")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="CategoryRequest",
 *     type="object",
 *     required={"name"},
 *
 *     @OA\Property(property="name", type="string", example="Новая категория"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Описание новой категории"),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, example=null),
 *     @OA\Property(property="sort_order", type="integer", example=0),
 *     @OA\Property(property="icon", type="string", format="binary", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="CategoryNotFound",
 *     type="object",
 *
 *     @OA\Property(property="status", type="string", example="error"),
 *     @OA\Property(property="message", type="string", example="Запрашиваемый ресурс не найден")
 * )
 */
class Category {}
