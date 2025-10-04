<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\UpdateProfileRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Services\User\UserProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class ProfileController extends ApiController
{
    /**
     * @group Профиль
     * Получить профиль текущего пользователя
     *
     * Этот эндпоинт возвращает данные профиля аутентифицированного пользователя.
     *
     * @authenticated
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Success",
     *   "data": {
     *     "id": 1,
     *     "first_name": "Иван",
     *     "last_name": "Иванов",
     *     "middle_name": "Иванович",
     *     "full_name": "Иванов Иван Иванович",
     *     "email": "ivan@example.com",
     *     "phone": "+79999999999",
     *     "address": "Не дом и не улица, www ленинград"
     *     "email_verified": true,
     *     "created_at": "2024-05-28T12:00:00+00:00",
     *     "updated_at": "2024-05-28T12:00:00+00:00"
     *   }
     * }
     */
    public function show(Request $request, UserProfileService $service): JsonResponse
    {
        $user = Auth::user();
        if (! ($user instanceof User)) {
            abort(401, 'Unauthorized');
        }
        $profile = $service->getProfile($user);

        return $this->successResponse(new UserResource($profile));
    }

    /**
     * @group Профиль
     * Редактировать профиль текущего пользователя
     *
     * Этот эндпоинт позволяет обновить данные профиля аутентифицированного пользователя.
     *
     * @authenticated
     *
     * @bodyParam first_name string required Имя пользователя. Example: Иван
     * @bodyParam last_name string required Фамилия пользователя. Example: Иванов
     * @bodyParam middle_name string Отчество пользователя. Example: Иванович
     * @bodyParam email string required Email пользователя. Example: ivan@example.com
     * @bodyParam phone string Телефон пользователя. Example: +79999999999
     * @bodyParam address string Адрес. Example: ул. Пример, д. 1
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Success",
     *   "data": {
     *     "id": 1,
     *     "first_name": "Иван",
     *     "last_name": "Иванов",
     *     "middle_name": "Иванович",
     *     "full_name": "Иванов Иван Иванович",
     *     "email": "ivan@example.com",
     *     "phone": "+79999999999",
     *     "address": "Не дом и не улица, www ленинград"
     *     "email_verified": true,
     *     "created_at": "2024-05-28T12:00:00+00:00",
     *     "updated_at": "2024-05-28T12:00:00+00:00"
     *   }
     * }
     */
    public function update(UpdateProfileRequest $request, UserProfileService $service): JsonResponse
    {
        $user = Auth::user();
        if (! ($user instanceof User)) {
            abort(401, 'Unauthorized');
        }
        $profile = $service->updateProfile($user, $request->validated());

        return $this->successResponse(new UserResource($profile));
    }
}
