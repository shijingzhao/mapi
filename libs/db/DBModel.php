<?php
namespace Libs\DB;

class DBModel
{
    protected static function getConn()
    {
        return \Libs\DB\Database::getConn();
    }

    public static function get($sql = '')
    {
        try {
            $result = self::getConn()->read($sql);
        }
        catch (\PDOException $e) {
            var_dump($e);
            die;
        }
        return $result;
    }
}
