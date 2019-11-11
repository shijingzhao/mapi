<?php

namespace Frame;

class Autoload
{
    /**
     * 项目根路径
     * @var array
     */
    private static $rootPath;
    /**
     * 类命名空间映射
     * @var array
     */
    private static $namespaceMap = [];

    /**
     * 注册自动加载函数
     */
    public static function register($path)
    {
        self::$rootPath = $path . DS;
        spl_autoload_register(['Frame\Autoload', 'loadClass']);
    }

    /**
     * 加载类名文件
     * @param   string  $class  类命名空间
     * @return
     */
    private static function loadClass($class)
    {
        if (isset(self::$namespaceMap[$class])) return true;

        $filePath = self::getFilePath($class);
        if (!file_exists($filePath)) return false;
        require_once($filePath);
        self::$namespaceMap[$class] = $filePath;

        return true;
    }

    /**
     * 获得加载类路径
     * @param   string  $class      类命名空间
     * @return  string  $filePath   文件路径
     */
    private static function getFilePath($class)
    {
        $dirInfo = explode('\\', $class);
        $file = array_pop($dirInfo);
        foreach ($dirInfo as &$v) {
            $v = strtolower($v);
        }
        unset($v);
        array_push($dirInfo, $file);
        return self::$rootPath . implode(DS, $dirInfo) . '.php';
    }
}
