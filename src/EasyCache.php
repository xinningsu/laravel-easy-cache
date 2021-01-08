<?php

namespace Sulao\EasyCache;

/**
 * Trait EasyCache
 *
 * @package Sulao\EasyCache
 */
trait EasyCache
{
    /**
     * Call this method to cache the result of the method in next call.
     * $class->cache()->get(1) will cache the result of $class->get(1);
     *
     * @param int|null    $ttl    if null, will use the ttl in config file,
     *                            if not specified in config file, then 3600
     * @param string|null $key    if null, will serialize the instance class
     *                            name, method name and parameters as key. Has
     *                            to specify key if parameters have closure
     * @param string|null $store  laravel cache store
     *
     * @return CachePlug|static
     */
    public function cache($ttl = null, $key = null, $store = null)
    {
        return new CachePlug($this, $ttl, $key, $store);
    }
}
