<?php
namespace Frame;

class Container
{
    /**
     * 容器对象实例
     * @var Container|Closure
     */
    protected static $instance;

    protected static function setInstance($instance)
    {
        self::$instance = $instance;
    }
}
