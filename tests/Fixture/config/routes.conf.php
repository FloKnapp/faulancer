<?php

return [
    'home' => [
        'path' => '/',
        'method' => 'GET',
        'action' => 'stubHome',
        'controller' => Faulancer\Fixture\Controller\DummyController::class
    ],
    'stubStatic' => [
        'path' => '/stub',
        'method' => 'GET',
        'action' => 'stubStatic',
        'controller' => Faulancer\Fixture\Controller\DummyController::class
    ],
    'stubDynamic' => [
        'path' => '/stub/(\w+)',
        'method' => 'GET',
        'action' => 'stubDynamic',
        'controller' => Faulancer\Fixture\Controller\DummyController::class
    ],
    'stubNoResponse' => [
        'path' => '/stub-no-response',
        'method' => 'GET',
        'action' => 'stubNoResponse',
        'controller' => Faulancer\Fixture\Controller\DummyController::class
    ],
    'stubNoMethod' => [
        'path' => '/stub-no-method',
        'method' => 'GET',
        'action' => 'stubNoMethod',
        'controller' => Faulancer\Fixture\Controller\DummyController::class
    ],
    'rest' => [
        'testRoute' => [
            'path' => '/api/v1/test',
            'controller' => \Faulancer\Fixture\Controller\ApiDummyController::class
        ],
        'testDynamicRoute' => [
            'path' => '/api/v1/test/(\w+)',
            'controller' => \Faulancer\Fixture\Controller\ApiDummyController::class
        ]
    ]
];