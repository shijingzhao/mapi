<?php
/**
 +------------------------------------------------------------+
 |   m-api [ A php framework ]                                |
 +------------------------------------------------------------+
 |   Copyright (c)                                            |
 +------------------------------------------------------------+
 |   Author  shijingzhao <https://github.com/shijingzhao>     |
 +------------------------------------------------------------+
 */
namespace FrameWork;

use Countable;
use ArrayAccess;
use IteratorAggregate;
use ReflectionClass;
use ReflectionMethod;
use ReflectionFunction;

class Container implements Countable, ArrayAccess, IteratorAggregate
{
    /**
     * 容器对象实例
     * @var Container|Closure
     */
    protected static $instance;

    /**
     * 容器中的对象实例
     * @var array
     */
    protected $instances = [];

    /**
     * 容器绑定标识
     * @var array
     */
    protected $bind = [];

    /**
     * 容器回调
     * @var array
     */
    protected $invokeCallback = [];

    /**
     *
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * 获取容器中对象实例
     * @param   string  $abstract   类名或标识名
     * @return  object
     */
    public function get($abstract)
    {
        if ($this->has($abstract)) {
            return $this->make($abstract);
        }
    }

    /**
     * 判断容器中是否存在类及标识
     */
    public function has($abstract): bool
    {
        return isset($this->bind[$abstract]) ||
            isset($this->instances[$abstract]);
    }

    /**
     * 设置当前对象实例
     * @param   object|Closure $instance
     * @return void
     */
    public static function setInstance($instance): void
    {
        self::$instance = $instance;
    }

    /**
     * 绑定一个对象实例到容器
     * @param   string  $abstract    类名或者标识
     * @param   object  $instance    类的实例
     * @return  $this
     */
    public function instance(String $abstract, $instance)
    {
        $abstract = $this->getAlias($abstract);

        $this->instances[$abstract] = $instance;
    }

    /**
     * 根据别名获取真实类名
     * @param   string  $abstract   类名或者标识
     * @return  string
     */
    public function getAlias(string $abstract): string
    {
        if (isset($this->bind[$abstract])) {
            $bind = $this->bind[$abstract];
            if (is_string($bind)) {
                return $this->getAlias($bind);
            }
        }
        return $abstract;
    }

    /**
     * 创建类的实例 如存在直接获取
     * @param   string  $abstract   类名或标识
     * @param   array   $vars       变量
     * @param   bool    $newInstance是否每次创建新的实例
     * @return  mixed
     */
    public function make(string $abstract, array $vars = [], bool $newInstance = false)
    {
        $abstract = $this->getAlias($abstract);

        if (isset($this->instances[$abstract]) && !$newInstance) {
            return $this->instances[$abstract];
        }

        if (isset($this->bind[$abstract]) &&
            $this->bind[$abstract] instanceof Closure
        ) {
            $object = $this->invokeFunction($this->bind[$abstract], $vars);
        }
        else {
            $object = $this->invokeClass($abstract, $vars);
        }

        if (!$newInstance) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    /**
     * 执行函数或者闭包方法
     * @param   string|Closure  $function   函数或者闭包
     * @param   array           $vars       参数
     * @return  mixed
     */
    public function invokeFunction($function, $vars = [])
    {
        try {
            $reflect = new ReflectionFunction($function);
            $args = $this->bindParams($reflect, $vars);
            if ($reflect->isClosure()) {
                return $function->__invoke(...$args);
            }
            else {
                return $reflect->invokeArgs($args);
            }
        } catch (ReflectionException $e) {
            throw new Exception('function not exists:'.$function, $function, 0, $e);
        }
    }

    /**
     * 调用反射执行类的实例化 支持依赖注入
     * @param
     * @param
     * @return
     */
    public function invokeClass(string $class, array $vars = [])
    {
        try {
            $reflect = new ReflectionClass($class);
            if ($reflect->hasMethod('__make')) {
                $method = new ReflectionMethod($class, '__make');
                if ($method->isPublic() && $method->isStatic()) {
                    $args = $this->bindParams($method, $vars);
                    return $method->invokeArgs(null, $args);
                }
            }

            $constructor = $reflect->getConstructor();
            $args = $constructor ? $this->bindParams($constructor, $vars) : [];
            $object = $reflect->newInstanceArgs($args);
            $this->invokeAfter($class, $object);

            return $object;
        } catch (ReflectionException $e) {
            throw new ClassNotFoundException('class not exists:'.$class, $class, $e);
        }
    }

    /**
     * invokeClass回调
     * @param   string  $class  类名
     * @param   object  $object 容器对象实例
     * @return  void
     */
    protected function invokeAfter(string $class, $object): void
    {
        if (isset($this->invokeCallback['*'])) {
            foreach ($this->invokeCallback['*'] as $callback) {
                $callback($object, $this);
            }
        }

        if (isset($this->invokeCallback[$class])) {
            foreach ($this->invokeCallback[$class] as $callback) {
                $callback($object, $this);
            }
        }
    }

    /**
     * 绑定参数
     * @param   $reflect    反射类
     * @param   $vars       参数
     * @return  array
     */
    public function bindParams($reflect, array $vars = []): array
    {
        if ($reflect->getNumberOfParameters() == 0) return [];

        // 数组类型, 数字数组时按顺序绑定参数
        reset($vars);
        $type = key($vars) === 0 ? 1 : 0;
        $params = $reflect->getParameters();
        $args = [];

        foreach ($params as $param) {
            $name       = $param->getName();
            // $lowerName  = Str::snake($name);
            $lowerName  = $name;
            $class      = $param->getClass();

            if ($class) {
                $args[] = $this->getObjectParam($class->getName(), $vars);
            }
            elseif (1 == $type && !empty($vars)) {
                $args[] = array_shift($vars);
            }
            elseif (0 == $type && isset($var[$name])) {
                $args[] = $vars[$name];
            }
            elseif (0 == $type && isset($var[$lowerName])) {
                $args[] = $var[$lowerName];
            }
            elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            }
            else {
                throw new InvalidArgumentException('method param miss:'.$name);
            }
        }

        return $args;
    }

    /**
     * 获取对象类型的参数值
     * @param   string  $className  类名
     * @param   array   $vars       参数
     * @return  mixed
     */
    protected function getObjectParam(string $className, array &$vars)
    {
        $array = $vars;
        $value = array_shift($array);

        if ($value instanceof $className) {
            $result = $value;
            array_shift($vars);
        }
        else {
            $result = $this->make($className);
        }

        return $result;
    }

    /**
     * Countable - 统计对象元素个数
     */
    public function count()
    {
        return count($this->instances);
    }

    /**
     * ArrayAccess - 检查元素是否存在
     */
    public function offsetExists($key)
    {

    }

    /**
     * ArrayAccess - 获取一个元素的值
     */
    public function offsetGet($key)
    {

    }

    /**
     * ArrayAccess - 设置一个元素的值
     */
    public function offsetSet($key, $value)
    {

    }

    /**
     * ArrayAccess - 复位一个元素
     */
    public function offsetUnset($key)
    {

    }

    /**
     * IteratorAggregate - 获取一个外部迭代器
     */
    public function getIterator()
    {

    }
}
