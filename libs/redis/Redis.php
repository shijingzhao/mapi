<?php
namespace Libs\Redis;

class Redis
{
    protected static $connections;

    protected static function connect()
    {
        $config = [
            'host' => '127.0.0.1',
            'port' => 6379,
            'pass' => '',
        ];

        $redis = new \Redis();
        $redis->connect(
            $config['host'],
            $config['port']
        );
        return $redis;
    }

    public static function getRedis()
    {
        $connect = self::connect();
        self::$connections = $connect;
        return self::$connections;
    }
}
