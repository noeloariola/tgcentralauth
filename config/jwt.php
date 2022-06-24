<?php

return [
    'header' => [
        "typ" => "JWT",
        "alg" => "HS256"
    ],
    'registered' => [
        "app_a" => env('GOOGLE_API_TOKEN', 'your-some-default-value'),
        "app_b" => env('GOOGLE_API_TOKEN', 'your-some-default-value')
    ],
];

  
