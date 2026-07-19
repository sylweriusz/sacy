<?php

namespace sacy\internal;

use sacy\Cache;

/**
 * Class RedisCache
 *  sample of rudimentary Redis cache
 * @package sacy\internal
 */
class RedisCache implements Cache
{
    private static $cache     = false;
    private static $connected = false;

    public function __construct()
    {
        if (!defined('REDIS_SERVER')) {
            define('REDIS_SERVER', 'localhost');
        }
        if (strstr(REDIS_SERVER, ':')) {
            [$server, $port] = explode(':', REDIS_SERVER);
        } else {
            $server = REDIS_SERVER;
            $port   = 6379;
        }
        if (!self::$connected) {
            try {
                self::$cache     = new \Redis();
                self::$connected = self::$cache->connect($server, $port, 5);
                self::$cache->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
            }catch (\Exception $e) {
                self::$connected = false;
            }
        }
    }

    public function get($key)
    {
        if (self::$connected) {
            try {
                return self::$cache->get(md5($key));
            } catch (\RedisException $e) {
                self::$connected = false;
            }
        }
    }

    public function set($key, $value)
    {
        if (self::$connected) {
            try {
                return self::$cache->set(md5($key), $value, 5);
            } catch (\RedisException $e) {
                self::$connected = false;
            }
        }
    }
}
