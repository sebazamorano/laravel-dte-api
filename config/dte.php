<?php

return [
    'environment' => env('DTE_ENVIRONMENT', 'maullin'),
    'server_provider' => env('DTE_SERVER_PROVIDER', 'local'),
    'document_verification_url' => env('DTE_DOCUMENT_VERIFICATION_URL', ''),
    'cron_mail' => env('DTE_CRON_MAIL', ''),
    'mail_reception_username' => env('DTE_MAIL_RECEPTION_USERNAME', ''),
    'mail_reception_password' => env('DTE_MAIL_RECEPTION_PASSWORD', ''),
    'mail_reception_host' => env('DTE_MAIL_RECEPTION_HOST', ''),
    'mail_reception_port' => env('DTE_MAIL_RECEPTION_PORT', '993')
];
