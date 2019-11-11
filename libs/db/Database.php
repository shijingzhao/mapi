<?php
namespace Libs\DB;

class Database
{
    private static $singletons;

    private $config = [
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'pass' => 'mysql_password',
        'db'   => 'tp'
    ];

    public static function getConn($database = 'database')
    {
        if (!isset(self::$singletons[$database])) {
            self::$singletons[$database] = new Database($database);
        }
        return self::$singletons[$database];
    }

    private function __construct($database)
    {
        $pdo = new PDO(
            $this->config['host'],
            $this->config['db'],
            $this->config['user'],
            $this->config['pass'],
            $this->config['port']
        );

        self::$singletons['master'] = $pdo;
    }

    public function read($sql)
    {
        $result = self::$singletons['master']->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        return $result->fetchAll();
    }
}
