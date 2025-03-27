<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\Auth\RegistrationService;
use Illuminate\Http\JsonResponse;

class RegisterController extends ApiController
{
    private RegistrationService $registrationService;

    /**
     * RegisterController constructor.
     */
    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     summary="Регистрация нового пользователя",
     *     description="Регистрирует нового пользователя в системе",
     *     operationId="register",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Успешная регистрация",
     *
     *         @OA\JsonContent(
     *             allOf={
     *
     *                 @OA\Schema(ref="#/components/schemas/ApiResponse"),
     *                 @OA\Schema(
     *
     *                     @OA\Property(
     *                         property="data",
     *                         ref="#/components/schemas/UserResource"
     *                     )
     *                 )
     *             }
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации",
     *
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     *
     * Handle user registration
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $user = $this->registrationService->register($request->validated());

        return $this->successResponse(
            new UserResource($user),
            'Регистрация успешно завершена. Пожалуйста, проверьте вашу почту для подтверждения email.',
        );
    }
}
