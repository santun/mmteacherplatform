<?php

return [
    'backend_uri' => env('APP_BACKEND_URI', 'admin'),

    'google_analytics_key' => env('GA_KEY', ''),
    'enable_google_analytics' => env('ENABLE_GA', false),
    'recaptcha_key' => env('GOOGLE_RECAPTCHA_KEY', 'test'),
    'recaptcha_secret' => env('GOOGLE_RECAPTCHA_SECRET', ''),
    'enable_preloader' => env('ENABLE_PRELOADER', false),

    'sharing_enabled' => env('SHARING_ENABLED', false),
    'sharing_publisher_id' => env('SHARING_PUBLISHER_ID', ''),
    'order_email_2' => env('ORDER_EMAIL_2', ''),
    'vimeo_client' => env('VIMEO_CLIENT', ''),
    'vimeo_secret' => env('VIMEO_SECRET', ''),
    'vimeo_access_token' => env('VIMEO_ACCESS', ''),
    'search_operator' => env('SEARCH_OPERATOR', 'LIKE'),
    'enable_article_notification' => env('ENABLE_ARTICLE_NOTIFICATION', false)
];
