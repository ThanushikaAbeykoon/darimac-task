<?php

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // Restrict in production
    'allowed_headers' => ['*'],
    'supports_credentials' => false,
];
