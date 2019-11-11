<?php
namespace FrameWork;

class Autoload
{
    /**
     * 跟路径
     * @var array
     */
    private static $path = [];

    /**
     * 类命名空间映射
     * @var array
     */
    private static $namespaceMap = [];

    /**
     * 应用启动注册
     * @param
     * @return
     */
    public static function register($rootPath)
    {
        self::$path = $rootPath;

        // 注册框架加载函数
        spl_autoload_register(['FrameWork\Autoload', 'autoloader']);
    }

    private static function autoloader($class)
    {
        $classInfo = explode('\\', $class);
        $file = array_pop($classInfo);
        foreach ($classInfo as &$v) {
            $v = strtolower($v);
        }
        unset($v);
        array_push($classInfo, $file);
        $classPath = self::$path . implode(DS, $classInfo);
        $classPath .= '.php';
        require($classPath);
    }
}
