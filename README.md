# Laravel Easy Cache

Easy way to cache the result of a service method on demand.

[![MIT licensed](https://img.shields.io/badge/license-MIT-blue.svg)](./LICENSE)
[![Build Status](https://api.travis-ci.org/xinningsu/laravel-easy-cache.svg?branch=master)](https://travis-ci.org/xinningsu/laravel-easy-cache)
[![Coverage Status](https://coveralls.io/repos/github/xinningsu/laravel-easy-cache/badge.svg?branch=master)](https://coveralls.io/github/xinningsu/laravel-easy-cache)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/xinningsu/laravel-easy-cache/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/xinningsu/laravel-easy-cache)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/xinningsu/laravel-easy-cache/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/g/xinningsu/laravel-easy-cache)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=xinningsu_laravel-easy-cache&metric=alert_status)](https://sonarcloud.io/dashboard?id=xinningsu_laravel-easy-cache)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=xinningsu_laravel-easy-cache&metric=reliability_rating)](https://sonarcloud.io/dashboard?id=xinningsu_laravel-easy-cache)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=xinningsu_laravel-easy-cache&metric=security_rating)](https://sonarcloud.io/dashboard?id=xinningsu_laravel-easy-cache)
[![Maintainability](https://api.codeclimate.com/v1/badges/18669386ce65532b228f/maintainability)](https://codeclimate.com/github/xinningsu/laravel-easy-cache/maintainability)

# Installation

Require this package with composer. 

```
composer require xinningsu/laravel-easy-cache

```

[Optional] Copy `config/easy-cache.php` of this package to `config/easy-cache.php` under laravel project and custom it. see [Global Configuration](#global-configuration) for more detail.


# Usage

Use EasyCache trait in a service class


```php
class News
{
    use \Sulao\EasyCache\EasyCache;

    public function getTopNews($limit = 5)
    {
        $news = [
            ['id' => 1, 'title' => 'news 1'],
            ['id' => 2, 'title' => 'news 2'],
            ['id' => 3, 'title' => 'news 3'],
            ['id' => 4, 'title' => 'news 4'],
            ['id' => 5, 'title' => 'news 5'],
        ];

        return array_slice($news, 0, $limit);
    }
}

```

Now the result of the service method can be cached on demand.

```php
$news = new News();

// without caching
$topNews = $news->getTopNews(2);

// cache it with default configuration, ttl: 3600,
// key: serialize class name, method name and parameters as cache key,
// store: laravel default cache store
// see Global Configuration below to custom default configuration.
$topNews = $news->cache()->getTopNews(2);

// or specify the ttl
$topNews = $news->cache(300)->getTopNews(2);

// specify the ttl and cache key,
// please notice that the cache key has to be specified if there is
// a closure in parameters, because closure can not be serialized.
$topNews = $news->cache(300, 'cache-key')->getTopNews(2);

// specify ttl, cache key and store,
// store is the store defined in laravel config/cache.php
$topNews = $news->cache(300, 'cache-key', 'array')->getTopNews(2);
```


# Global Configuration

copy config/easy-cache.php of this package to config/easy-cache.php under laravel project, custom it on demand.

```php
return [
    // If the ttl parameter is not specified when calling cache method,
    // then use this one, default value is 3600.
    'ttl' => 3600,
    
    // Value can be the store defined in config/cache.php of laravel project,
    // such as memcached, redis ... If null, using laravel default cache store.
    'store' => null,

    // This prefix will be added to the front of each cache key, so it can
    // easily refresh the whole cache by changing this. default value is null.
    'prefix' => null,

    // If this specified, the caches in the page can be refreshed via query string,
    // see Refresh Page Cache below.
    'refresh_key' => null,
];

```

# Refresh Page Cache


Firstly have to defined `refresh_key` in [config/easy-cache.php](#global-configuration), such as

```
    'refresh_key' => 'clear_cache',
```

Then open the page and add the query string like this

```
http://localhots/?clear_cache=1
```

Will refresh all the caches of that page, notice that just the caches of the page, not all the caches in the store.

# License

[MIT](./LICENSE)
