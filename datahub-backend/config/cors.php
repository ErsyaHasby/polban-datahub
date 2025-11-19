<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CORS Paths
    |--------------------------------------------------------------------------
    |
    | You can enable CORS for 1 or multiple paths.
    | Example: ['api/*']
    */
    'paths' => ['api/*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Methods
    |--------------------------------------------------------------------------
    |
    | Methods allowed in the CORS requests.
    | Example: ['GET', 'POST', 'PUT', 'DELETE']
    */
    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    |
    | A list of origins allowed to make CORS requests.
    | Example: ['http://localhost:3000']
    */
    'allowed_origins' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins Patterns
    |--------------------------------------------------------------------------
    |
    | A list of allowed origins patterns that match against the request origin.
    | Example: ['/localhost:\d+/']
    */
    'allowed_origins_patterns' => [],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    |
    | A list of headers allowed in the CORS requests.
    | Example: ['Content-Type', 'X-Requested-With']
    */
    'allowed_headers' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Exposed Headers
    |--------------------------------------------------------------------------
    |
    | A list of headers your application is allowed to expose to the browser.
    | Example: ['Authorization', 'X-Total-Count']
    */
    'exposed_headers' => [],

    /*
    |--------------------------------------------------------------------------
    | Max Age
    |--------------------------------------------------------------------------
    |
    | The maximum age the browser is allowed to cache the preflight request.
    */
    'max_age' => 0,

    /*
    |--------------------------------------------------------------------------
    | Supports Credentials
    |--------------------------------------------------------------------------
    |
    | Whether or not your application supports credentials.
    */
    'supports_credentials' => false,

];
