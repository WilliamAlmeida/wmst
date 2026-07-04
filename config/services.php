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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'mcp' => [
        // Token estático (Bearer) exigido pelo servidor MCP web em produção.
        'token' => env('MCP_TOKEN'),
    ],

    'google' => [
        // Caminho para o JSON da service account (fora do git).
        // Usa ?: para que GOOGLE_SA_CREDENTIALS vazio caia no padrão (env() vazio
        // retorna '' e não dispararia o default).
        'credentials' => env('GOOGLE_SA_CREDENTIALS') ?: storage_path('app/google/service-account.json'),
        // Propriedade do Search Console (propriedade de domínio).
        'gsc_site' => env('GSC_SITE_URL', 'sc-domain:wmst.com.br'),
        // ID numérico da propriedade GA4.
        'ga4_property' => env('GA4_PROPERTY_ID', '544115334'),
    ],

];
