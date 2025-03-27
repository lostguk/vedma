<?php

return [
    'pages' => [
        'dashboard' => [
            'title' => 'Панель управления',
        ],
    ],

    'layout' => [
        'sidebar' => [
            'groups' => [
                'users' => 'Пользователи',
                'settings' => 'Настройки',
            ],
        ],
        'user-menu' => [
            'profile' => [
                'label' => 'Профиль',
            ],
            'logout' => [
                'label' => 'Выйти',
            ],
        ],
    ],

    'actions' => [
        'create' => [
            'label' => 'Создать',
        ],
        'edit' => [
            'label' => 'Редактировать',
        ],
        'delete' => [
            'label' => 'Удалить',
        ],
        'save' => [
            'label' => 'Сохранить',
        ],
        'cancel' => [
            'label' => 'Отмена',
        ],
    ],

    'resources' => [
        'pages' => [
            'create' => [
                'buttons' => [
                    'create' => [
                        'label' => 'Создать',
                    ],
                ],
                'messages' => [
                    'created' => 'Создано успешно',
                ],
            ],
            'edit' => [
                'buttons' => [
                    'save' => [
                        'label' => 'Сохранить изменения',
                    ],
                ],
                'messages' => [
                    'saved' => 'Сохранено успешно',
                ],
            ],
        ],
    ],

    'widgets' => [
        'account' => [
            'label' => 'Аккаунт',
        ],
    ],

    'navigation' => [
        'group' => [
            'label' => 'Навигация',
        ],
    ],
];
