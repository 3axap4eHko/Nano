<?php

return [
    'types' => [
        'command' => 'Nano\Console\Route\Route'
    ],
    'defaultType' => 'http',
    'routes' => [
        'test' => [
            'type' => 'command',
            'pattern' => 'test',
            'description' => 'test command',
            'defaults' => [
                '_handler' => 'GH:Index:index'
            ]
        ],
        'home' => [
            'pattern' => '/',
            'defaults' => [
                '_handler' => 'GH:Index:index'
            ]

        ]
    ]
];