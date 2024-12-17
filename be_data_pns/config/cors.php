<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // Specify paths that allow CORS requests
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'storage/*'],

    // Allowed HTTP methods for CORS requests
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],

    // Allowed origins (specific domains or '*')
    'allowed_origins' => ['http://localhost:5173',],

    // Allowed origin patterns (use regex if needed)
    'allowed_origins_patterns' => [],

    // Allowed HTTP headers in CORS requests
    'allowed_headers' => ['Content-Type', 'X-Requested-With', 'Authorization'],

    // Headers exposed to the client
    'exposed_headers' => ['Authorization'],

    // Maximum age for CORS preflight requests (in seconds)
    'max_age' => 86400,

    // Whether or not credentials (cookies, HTTP auth) are supported
    'supports_credentials' => true,

];
