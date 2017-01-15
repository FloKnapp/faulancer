<?php

return [
    'home' => [
        'path' => '/',
        'method' => 'GET',
        'name' => 'stub',
        'action' => 'stubHome',
        'controller' => Faulancer\Fixture\Controller\DummyController::class
    ],
    'stub' => [
        'path' => '/stub',
        'method' => 'GET',
        'name' => 'stub',
        'action' => 'stubStatic',
        'controller' => Faulancer\Fixture\Controller\DummyController::class
    ],
    'stubDynamic' => [
        'path' => '/stub/(\w+)',
        'method' => 'GET',
        'name' => 'stubDynamic',
        'action' => 'stubDynamic',
        'controller' => Faulancer\Fixture\Controller\DummyController::class
    ]
];