<?php

use Nano\Service\ServiceManager as SM;

return [
    SM::SERVICE_LOADER => [

    ],
    SM::SERVICE_MODULES => [
        realpath(__DIR__ . '/../src') => ['GH']
    ],
    SM::SERVICE_SERVICES => [

    ],
    SM::SERVICE_DATABASE => [
        'host' => 'localhost'
    ]
];