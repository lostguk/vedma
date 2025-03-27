<?php

return [
    'pages' => [
        'edit' => [
            'title' => 'Редактирование :label',
        ],
        'create' => [
            'title' => 'Создание :label',
        ],
        'list' => [
            'title' => ':label',
        ],
    ],

    'components' => [
        'pagination' => [
            'showing_results' => 'Показано с :first по :last из :total',
            'results_per_page' => 'Записей на странице',
        ],
    ],

    'actions' => [
        'edit' => [
            'label' => 'Редактировать',
        ],
        'create' => [
            'label' => 'Создать',
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

    'table' => [
        'actions' => [
            'label' => 'Действия',
        ],
        'bulk_actions' => [
            'label' => 'Массовые действия',
        ],
    ],

    'forms' => [
        'actions' => [
            'save' => [
                'label' => 'Сохранить изменения',
            ],
            'cancel' => [
                'label' => 'Отмена',
            ],
        ],
        'fields' => [
            'meta_title' => [
                'label' => 'Meta Title',
            ],
            'meta_description' => [
                'label' => 'Meta Description',
            ],
            'url' => [
                'label' => 'URL',
                'helper_text' => 'URL будет сформирован автоматически из названия, но вы можете изменить его вручную',
            ],
        ],
    ],

    'resources' => [
        'product' => [
            'navigation_group' => 'Каталог',
            'navigation_label' => 'Товары',
            'model_label' => 'товар',
            'plural_model_label' => 'товары',
        ],
        'category' => [
            'navigation_group' => 'Каталог',
            'navigation_label' => 'Категории',
            'model_label' => 'категорию',
            'plural_model_label' => 'категории',
        ],
        'user' => [
            'navigation_group' => 'Управление',
            'navigation_label' => 'Пользователи',
            'model_label' => 'пользователя',
            'plural_model_label' => 'пользователей',
        ],
    ],
];
