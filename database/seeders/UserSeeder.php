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
            'country' => 'Россия',
            'region' => 'Московская область',
            'city' => 'Москва',
            'postal_code' => '123456',
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
            'country' => 'Россия',
            'region' => 'Московская область',
            'city' => 'Москва',
            'postal_code' => '123456',
            'address' => 'ул. Пушкина, д. 1',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем администраторов (в любом окружении)
        foreach ($this->administrators as $adminData) {
            $this->createUser($adminData);
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
    private function createUser(array $userData): User
    {
        return User::factory()->create([
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'middle_name' => $userData['middle_name'],
            'email' => $userData['email'],
            'password' => bcrypt($userData['password']),
            'phone' => $userData['phone'],
            'country' => $userData['country'],
            'region' => $userData['region'],
            'city' => $userData['city'],
            'postal_code' => $userData['postal_code'],
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
