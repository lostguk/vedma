<?php

return [
    'required' => 'Поле :attribute обязательно для заполнения.',
    'email' => 'Поле :attribute должно быть действительным электронным адресом.',
    'max' => [
        'string' => 'Поле :attribute не может быть больше :max символов.',
    ],
    'min' => [
        'string' => 'Поле :attribute должно быть не меньше :min символов.',
    ],
    'unique' => 'Такое значение поля :attribute уже существует.',
    'confirmed' => 'Поле :attribute не совпадает с подтверждением.',
    'string' => 'Поле :attribute должно быть строкой.',

    'attributes' => [
        'email' => 'Email',
        'password' => 'Пароль',
        'first_name' => 'Имя',
        'last_name' => 'Фамилия',
        'middle_name' => 'Отчество',
        'phone' => 'Телефон',
        'country' => 'Страна',
        'region' => 'Регион',
        'city' => 'Город',
        'postal_code' => 'Почтовый индекс',
        'address' => 'Адрес',
    ],
];
