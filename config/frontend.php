<?php

return [
    'discovery' => [
        'auth' => env('FRONTEND_DISCOVERY_AUTH', sprintf('auth.%s', config('app.domain'))),
    ]
];
