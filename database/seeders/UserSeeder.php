<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Количество тестовых пользователей для создания в dev-окружении
     */
    private const DEV_USERS_COUNT = 10;

    /**
     * Администраторы системы
     */
    private array $administrators = [
        [
            'first_name' => 'Admin',
            'last_name' => 'System',
            'middle_name' => 'Root',
            'email' => 'admin@admin.ru',
            'password' => 'admin',
            'phone' => '+7 999 999 99 99',
            'address' => 'ул. Администраторская, д. 1',
        ],
    ];

    /**
     * Тестовые пользователи для разработки
     */
    private array $developmentUsers = [
        [
            'first_name' => 'Test',
            'last_name' => 'User',
            'middle_name' => 'Admin',
            'email' => 'test@example.com',
            'password' => 'password',
            'phone' => '+7 999 123 45 67',
            'address' => 'ул. Пушкина, д. 1',
        ],
        [
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'middle_name' => 'Иванович',
            'email' => 'user@example.com',
            'password' => 'password123',
            'phone' => '+7 999 123 45 67',
            'address' => 'ул. Примерная, д. 1, кв. 1',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем администраторов (в любом окружении)
        foreach ($this->administrators as $adminData) {
            $this->createUser($adminData, true);
        }

        // В dev-окружении создаем тестовых пользователей
        if ($this->shouldSeedTestData()) {
            // Создаем предопределенных тестовых пользователей
            foreach ($this->developmentUsers as $userData) {
                $this->createUser($userData);
            }

            // Создаем случайных тестовых пользователей
            $this->createRandomUsers();
        }
    }

    /**
     * Создает пользователя с заданными данными
     */
    private function createUser(array $userData, bool $isAdmin = false): User
    {
        return User::factory()->create([
            'is_admin' => $isAdmin,
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'middle_name' => $userData['middle_name'],
            'email' => $userData['email'],
            'password' => bcrypt($userData['password']),
            'phone' => $userData['phone'],
            'address' => $userData['address'],
        ]);
    }

    /**
     * Создает случайных тестовых пользователей
     */
    private function createRandomUsers(): void
    {
        User::factory(self::DEV_USERS_COUNT)->create();
    }

    /**
     * Определяет, нужно ли создавать тестовые данные
     */
    private function shouldSeedTestData(): bool
    {
        return app()->environment('local', 'development');
    }
}
