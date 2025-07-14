<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @mixin \Illuminate\Http\Request
 */
abstract class ApiRequest extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY),
        );
    }

    /**
     * Handle a failed authorization attempt.
     *
     *
     * @throws HttpResponseException
     */
    protected function failedAuthorization(): void
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'This action is unauthorized.',
                'errors' => [
                    'authorization' => ['You do not have the required permissions.'],
                ],
            ], Response::HTTP_FORBIDDEN),
        );
    }
}
