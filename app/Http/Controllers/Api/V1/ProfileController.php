<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\V1\UserResource;
use App\Services\User\UserProfileService;
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
     *     "address": {
     *       "country": "Россия",
     *       "region": "Москва",
     *       "city": "Москва",
     *       "postal_code": "101000",
     *       "address": "ул. Пример, д. 1"
     *     },
     *     "email_verified": true,
     *     "created_at": "2024-05-28T12:00:00+00:00",
     *     "updated_at": "2024-05-28T12:00:00+00:00"
     *   }
     * }
     */
    public function show(Request $request, UserProfileService $service): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        if (! ($user instanceof \App\Models\User)) {
            abort(401, 'Unauthorized');
        }
        $profile = $service->getProfile($user);

        return $this->successResponse(new UserResource($profile));
    }
}
