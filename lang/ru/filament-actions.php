<?php

return [
    'modal' => [
        'requires_confirmation_subheading' => 'Вы уверены, что хотите это сделать?',
    ],

    'edit' => [
        'single' => [
            'label' => 'Редактировать',
            'modal' => [
                'heading' => 'Редактировать :label',
                'actions' => [
                    'save' => [
                        'label' => 'Сохранить изменения',
                    ],
                ],
            ],
            'messages' => [
                'saved' => 'Сохранено',
            ],
        ],
    ],

    'view' => [
        'single' => [
            'label' => 'Просмотр',
            'modal' => [
                'heading' => 'Просмотр :label',
            ],
        ],
    ],

    'delete' => [
        'single' => [
            'label' => 'Удалить',
            'modal' => [
                'heading' => 'Удалить :label',
                'subheading' => 'Вы уверены, что хотите это сделать?',
                'actions' => [
                    'delete' => [
                        'label' => 'Удалить',
                    ],
                ],
            ],
            'messages' => [
                'deleted' => 'Удалено',
            ],
        ],
        'multiple' => [
            'label' => 'Удалить выбранные',
            'modal' => [
                'heading' => 'Удалить выбранные :label',
                'subheading' => 'Вы уверены, что хотите это сделать?',
                'actions' => [
                    'delete' => [
                        'label' => 'Удалить',
                    ],
                ],
            ],
            'messages' => [
                'deleted' => 'Удалено',
            ],
        ],
    ],

    'force_delete' => [
        'single' => [
            'label' => 'Удалить навсегда',
            'modal' => [
                'heading' => 'Удалить навсегда :label',
                'subheading' => 'Вы уверены, что хотите это сделать?',
                'actions' => [
                    'force_delete' => [
                        'label' => 'Удалить навсегда',
                    ],
                ],
            ],
            'messages' => [
                'deleted' => 'Удалено навсегда',
            ],
        ],
        'multiple' => [
            'label' => 'Удалить выбранные навсегда',
            'modal' => [
                'heading' => 'Удалить выбранные :label навсегда',
                'subheading' => 'Вы уверены, что хотите это сделать?',
                'actions' => [
                    'force_delete' => [
                        'label' => 'Удалить навсегда',
                    ],
                ],
            ],
            'messages' => [
                'deleted' => 'Удалено навсегда',
            ],
        ],
    ],

    'restore' => [
        'single' => [
            'label' => 'Восстановить',
            'modal' => [
                'heading' => 'Восстановить :label',
                'subheading' => 'Вы уверены, что хотите это сделать?',
                'actions' => [
                    'restore' => [
                        'label' => 'Восстановить',
                    ],
                ],
            ],
            'messages' => [
                'restored' => 'Восстановлено',
            ],
        ],
        'multiple' => [
            'label' => 'Восстановить выбранные',
            'modal' => [
                'heading' => 'Восстановить выбранные :label',
                'subheading' => 'Вы уверены, что хотите это сделать?',
                'actions' => [
                    'restore' => [
                        'label' => 'Восстановить',
                    ],
                ],
            ],
            'messages' => [
                'restored' => 'Восстановлено',
            ],
        ],
    ],

    'filter' => [
        'label' => 'Фильтр',
        'modal' => [
            'heading' => 'Фильтр',
            'actions' => [
                'apply' => [
                    'label' => 'Применить',
                ],
                'reset' => [
                    'label' => 'Сбросить',
                ],
            ],
        ],
    ],
];
