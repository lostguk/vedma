<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Auth;

use Tests\TestCase;

final class VerifyRegistrationSignatureTest extends TestCase
{
    public function test_invalid_signature_returns_russian_message(): void
    {
        $this->getJson('/api/v1/verify-registration/1/deadbeef?expires=9999999999&signature=invalid')
            ->assertForbidden()
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Неверная или истекшая ссылка подтверждения. Запросите новое письмо.')
            ->assertJsonPath('code', 'invalid_signature');
    }
}
