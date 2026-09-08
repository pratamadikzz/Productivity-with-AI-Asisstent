<?php

return [

    'provider' => env('AI_PROVIDER', 'gemini'),

    'gemini' => [

        'api_key' => env('GEMINI_API_KEY'),

        'model' => env(
            'GEMINI_MODEL',
            'gemini-2.5-flash'
        ),

    ],

];