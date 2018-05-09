<?php

return array(

    /*
     |--------------------------------------------------------------------------
     | HttpCache Settings
     |--------------------------------------------------------------------------
     |
     | Enable the HttpCache to cache public resources, with a shared max age (or TTL)
     | Enable ESI for edge side includes (parts that can be cached separate)
     | Set the cache to a writable dir, outside the document root.
     |
     */
    'enabled' => true,
    'esi' => false,
    'cache_dir' => storage_path('httpcache'),

    /*
     |--------------------------------------------------------------------------
     | Extra options
     |--------------------------------------------------------------------------
     |
     | Configure the default HttpCache options. See for a list of options:
     | http://symfony.com/doc/current/book/http_cache.html#symfony2-reverse-proxy
     |
     */
    'options' => [
        'debug'                  => env('APP_DEBUG', false),
        'default_ttl'            => 60,
        'private_headers'        => ['Authorization'],
        'allow_reload'           => false, // "ctrl+shift+r" force refresh
        'allow_revalidate'       => false, // "ctrl+r" refresh button
        'stale_while_revalidate' => 2,
        'stale_if_error'         => 60,
    ],
);
