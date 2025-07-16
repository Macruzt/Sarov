<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    
   'firebase' => [
    'api_key' => 'AIzaSyDQ-wgULEaXwFP2SpsB7lXBhqr-7e8RbyI',
    'auth_domain' => 'operation-andrew.firebaseapp.com',
    'database_url' => 'https://operation-andrew-default-rtdb.firebaseio.com',
    'project_id' => 'operation-andrew',
    'storage_bucket' => 'operation-andrew.appspot.com',
    'messaging_sender_id' => '623167516498',
    'app_id' => '1:623167516498:web:384cc215bc9a28de4b548b',
    'measurement_id' => 'G-BTYJ51MN21',
], 

];
