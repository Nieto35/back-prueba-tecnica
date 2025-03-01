<?php

return [
    'discovery' => [
        'auth' => env('FRONTEND_DISCOVERY_AUTH', sprintf('auth.%s', config('app.domain'))),
        'app' => env('FRONTEND_DISCOVERY_APP', sprintf('app.%s', config('app.domain'))),
    ]
];
