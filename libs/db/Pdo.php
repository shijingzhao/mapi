<?php
namespace Libs\DB;

class Pdo extends \PDO
{
    public function __construct($host, $db, $user, $pass, $port = 3360)
    {
        $dsn = "mysql:dbname={$db};host={$host};port={$port};";

        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "utf8";',
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE,
            PDO::ATTR_TIMEOUT => 3
        ];

        parent::__construct($dsn, $user, $pass, $options);
    }
}
