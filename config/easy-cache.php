<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache ttl
    |--------------------------------------------------------------------------
    |
    | When calling the cache method, if the ttl parameter is not specified,
    | using default value(null), Then will use this default Cache ttl.
    |
    */

    'ttl' => 3600,

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | Value can be the store defined in config/cache.php of laravel project.
    | If null, using laravel default cache store.
    |
    */

    'store' => null,

    /*
    |--------------------------------------------------------------------------
    | Global Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | If prefix defined, each cache key will add this prefix in the front.
    | So it can easily refresh the whole cache by changing this prefix.
    |
    */

    'prefix' => null,


    /*
    |--------------------------------------------------------------------------
    | Refresh key
    |--------------------------------------------------------------------------
    |
    | If this specified, you can refresh a page cache via query string, such as
    | 'refresh_key' => 'clear_cache', then http://localhots/?clear_cache=1 will
    | refresh all the cache of that page.
    |
    */
    'refresh_key' => null,
];
