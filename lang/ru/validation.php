<?php

return [
    'unique' => ':attribute с таким значением уже существует.',
    'required' => 'Поле ":attribute" является обязательным.',
    'length' => 'Поле ":attribute" должно содержать не менее :min символов.',

    'min' => [
        'numeric' => 'Поле :attribute должно быть не менее :min.',
        'string' => 'Поле ":attribute" должно содержать не менее :min символов.',
        'array' => 'Поле ":attribute" должно содержать не менее :min элементов.',
    ],
    'max' => [
        'array' => 'Поле ":attribute" должно содержать не более :max элементов.',
        'numeric' => 'Поле :attribute не должно превышать :max.',
    ]
];
