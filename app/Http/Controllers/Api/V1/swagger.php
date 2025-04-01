<?php

declare(strict_types=1);

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Магазин магических товаров API",
 *     description="API документация для магазина магических товаров",
 *
 *     @OA\Contact(
 *         email="admin@example.com",
 *         name="API Support",
 *         url="https://example.com/support"
 *     ),
 *
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url="/api/v1",
 *     description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 *
 * @OA\Tag(
 *     name="Auth",
 *     description="Аутентификация и авторизация"
 * )
 * @OA\Tag(
 *     name="Categories",
 *     description="Управление категориями"
 * )
 */
