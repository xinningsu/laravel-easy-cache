<?php

namespace Sulao\EasyCache;

use Illuminate\Contracts\Cache\Repository;

/**
 * Class CachePlug
 *
 * @package Sulao\EasyCache
 */
class CachePlug
{
    /**
     * @var object
     */
    protected $instance;

    /**
     * @var Repository
     */
    protected $cache;

    /**
     * @var int
     */
    protected $ttl;

    /**
     * @var string|null
     */
    protected $key;

    /**
     * @var string|null
     */
    protected $prefix;

    /**
     * @var bool
     */
    protected $refresh;

    /**
     * CacheMethod constructor.
     *
     * @param object      $instance
     * @param int|null    $ttl
     * @param string|null $key
     * @param string|null $store
     */
    public function __construct(
        $instance,
        $ttl = null,
        $key = null,
        $store = null
    ) {
        $this->instance = $instance;

        $this->ttl = !is_null($ttl) ? $ttl : (config('easy-cache.ttl') ?: 3600);
        $this->key = $key;

        $store = !is_null($store) ? $store : config('easy-cache.store');
        $this->cache = app('cache')->store($store);

        $prefix = config('easy-cache.prefix');
        $this->prefix = $prefix ? $prefix . '::' : '';

        $refreshKey = config('easy-cache.refresh_key');
        $this->refresh = $refreshKey && app('request')->get($refreshKey);
    }

    /**
     * Static call method
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $cacheKey = $this->key ?: md5(
            get_class($this->instance) . '::' . serialize(func_get_args())
        );
        $cacheKey = $this->prefix . $cacheKey;

        if (empty($this->refresh)) {
            $result = $this->cache->get($cacheKey);

            if (!is_null($result)) {
                return $result;
            }
        }

        $result = call_user_func_array([$this->instance, $name], $arguments);
        $this->cache->put($cacheKey, $result, $this->ttl);

        return $result;
    }
}
