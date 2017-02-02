<?php

return [
    'viewsRoot'       => __DIR__ . '/../views',
    'projectRoot'     => __DIR__ . '/..',
    'applicationRoot' => __DIR__ . '/../src',
    'namespacePrefix' => 'Faulancer\Fixture',
    'translation'     => require __DIR__ . '/translation.conf.php',
    'routes'          => require __DIR__ . '/routes.conf.php',
    'recursiveTest'   => ['layer' => ['layer2' => 'test']]
];