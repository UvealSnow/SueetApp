<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
        ],
        'db' => [
            'host' => 'localhost',
            'user' => 'usnow',
            'pass' => '48kgeia8!',
            'dbname' => 'Sueet',
        ],
    ],
];
