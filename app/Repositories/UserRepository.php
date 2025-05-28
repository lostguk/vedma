<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Str;

/**
 * Репозиторий для работы с пользователями
 */
final class UserRepository extends BaseRepository
{
    /**
     * UserRepository constructor.
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Создать нового пользователя с токеном верификации
     *
     * @param array{
     *     email: string,
     *     password: string,
     *     first_name: string,
     *     last_name: string,
     *     middle_name?: string|null,
     *     phone?: string|null
     * } $payload Данные пользователя
     */
    public function createWithVerification(array $payload): User
    {
        // Удаляем поле password_confirmation, так как его нет в таблице users
        if (isset($payload['password_confirmation'])) {
            unset($payload['password_confirmation']);
        }

        $payload['email_verification_token'] = Str::random(64);

        /** @var User $user */
        $user = $this->create($payload);

        return $user;
    }

    /**
     * Найти пользователя по ID
     */
    public function findById(
        int $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = [],
    ): ?User {
        /** @var User|null $user */
        $user = $this->model->select($columns)->with($relations)->find($modelId);
        if ($user && $appends) {
            $user->append($appends);
        }

        return $user;
    }

    /**
     * Обновить пользователя по ID
     */
    public function updateById(int $id, array $data): User
    {
        /** @var User $user */
        $user = $this->findById($id);
        $user->update($data);

        return $user->fresh();
    }
}
