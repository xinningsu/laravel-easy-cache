<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Illuminate\Cache\CacheManager;
use Illuminate\Config\Repository;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Sulao\EasyCache\CachePlug;


class EasyCacheTest extends \PHPUnit\Framework\TestCase
{
    public function testCachePlug()
    {
        $this->getApp();

        $news = new News();
        $cachePlug = new CachePlug($news);
        $this->assertEquals(
            $cachePlug->getTopNews(1),
            $cachePlug->getTopNews(1)
        );
        $this->assertEquals(1, $news->offset);

        $cachePlug->getTopNews(1);
        $cachePlug->getTopNews(1);
        $this->assertEquals(1, $news->offset);

        $this->assertEquals(
            $cachePlug->getTopNews(2),
            $cachePlug->getTopNews(2)
        );
        $this->assertEquals(2, $news->offset);

        $this->assertNotEquals(
            $cachePlug->getTopNews(1),
            $cachePlug->getTopNews(2)
        );
        $this->assertEquals(2, $news->offset);
    }

    public function testCachePlugWitchConfig()
    {
        $app = $this->getApp();
        $app['config']->set('easy-cache', [
            'ttl' => 3600,
            'store' => 'array',
            'prefix' => null,
            'refresh_key' => 'clear_cache',
        ]);

        $news = new News();
        $cachePlug = new CachePlug($news);
        $this->assertEquals(
            $cachePlug->getTopNews(5),
            $cachePlug->getTopNews(5)
        );
        $this->assertEquals(1, $news->offset);

        $this->assertEquals(
            $cachePlug->getTopNews(6),
            $cachePlug->getTopNews(6)
        );
        $this->assertEquals(2, $news->offset);

        $this->assertNotEquals(
            $cachePlug->getTopNews(5),
            $cachePlug->getTopNews(6)
        );
        $this->assertEquals(2, $news->offset);

        $request = new Request(['clear_cache' => 1], [], [], [], [], [
            'HTTP_HOST' => 'localhost',
            'REQUEST_URI' => '/?clear_cache=1',
            'REQUEST_METHOD' => 'GET',
            'QUERY_STRING' => 'clear_cache=1',
        ]);
        $app->instance('request', $request);

        $cachePlug = new CachePlug($news);
        $news->offset = 0;
        $this->assertNotEquals(
            $cachePlug->getTopNews(5),
            $cachePlug->getTopNews(5)
        );

        $this->assertEquals(2, $news->offset);

    }

    public function testEasyCache()
    {
        $this->getApp();

        $news = new News();
        $this->assertNotEquals($news->getTopNews(3), $news->getTopNews(3));
        $this->assertEquals(2, $news->offset);

        $this->assertEquals(
            $news->cache()->getTopNews(3),
            $news->cache()->getTopNews(3)
        );
        $this->assertEquals(3, $news->offset);

        $news->cache()->getTopNews(3);
        $news->cache(300, 'cache-key')->getTopNews(3);
        $news->cache(300, 'cache-key')->getTopNews(3);
        $this->assertEquals(4, $news->offset);

        $this->assertNotEquals(
            $news->cache(300, 'cache-key', 'array')->getTopNews(3),
            $news->cache(300, 'cache-key', 'array2')->getTopNews(3)
        );
        $this->assertEquals(5, $news->offset);

        $this->assertEquals(
            $news->cache(300, 'cache-key', 'array2')->getTopNews(3),
            $news->cache(300, 'cache-key', 'array2')->getTopNews(3)
        );
        $this->assertEquals(5, $news->offset);
    }

    public function testEasyCacheWithConfig()
    {
        $app = $this->getApp();
        $app['config']->set('easy-cache', [
            'ttl' => 3600,
            'store' => 'array',
            'prefix' => null,
            'refresh_key' => 'clear_cache',
        ]);

        $news = new News();
        $this->assertNotEquals($news->getTopNews(2), $news->getTopNews(2));
        $this->assertEquals(2, $news->offset);

        $this->assertEquals(
            $news->cache()->getTopNews(2),
            $news->cache()->getTopNews(2)
        );
        $this->assertEquals(3, $news->offset);

        $news->cache()->getTopNews(2);
        $news->cache(300, 'cache-key')->getTopNews(2);
        $news->cache(300, 'cache-key')->getTopNews(2);
        $this->assertEquals(4, $news->offset);

        $this->assertNotEquals(
            $news->cache(300, 'cache-key', 'array')->getTopNews(2),
            $news->cache(300, 'cache-key', 'array2')->getTopNews(2)
        );
        $this->assertEquals(5, $news->offset);

        $this->assertEquals(
            $news->cache(300, 'cache-key', 'array2')->getTopNews(2),
            $news->cache(300, 'cache-key', 'array2')->getTopNews(2)
        );
        $this->assertEquals(5, $news->offset);

        $request = new Request(['clear_cache' => 1], [], [], [], [], [
            'HTTP_HOST' => 'localhost',
            'REQUEST_URI' => '/?clear_cache=1',
            'REQUEST_METHOD' => 'GET',
            'QUERY_STRING' => 'clear_cache=1',
        ]);
        $app->instance('request', $request);

        $news->offset = 0;
        $this->assertNotEquals(
            $news->cache()->getTopNews(2),
            $news->cache()->getTopNews(2)
        );
        $this->assertEquals(2, $news->offset);

        $this->assertNotEquals(
            $news->cache(300, 'cache-key')->getTopNews(2),
            $news->cache(300, 'cache-key')->getTopNews(2)
        );
        $this->assertEquals(4, $news->offset);
    }

    protected function getApp()
    {
        $app = new Application(__DIR__);

        $events = new Dispatcher();
        $app['events'] = $events;

        $cacheManager = new CacheManager($app);
        $app['cache'] = $cacheManager;

        $config = new Repository();
        $config->set('cache', [
            'default' => 'array',
            'stores' => [
                'array' => [
                    'driver' => 'array',
                    'serialize' => false,
                ],
                'array2' => [
                    'driver' => 'array',
                    'serialize' => false,
                ],
            ],
            'prefix' => 'laravel',
        ]);
        $app->instance('config', $config);

        $request = new Request([], [], [], [], [], []);
        $app->instance('request', $request);

        return $app;
    }
}
