<?php
namespace Frame;

use \Closure;

class App extends Container
{
    /**
     * 框架实例
     * @var object
     */
    public static $app;

    /**
     * 框架执行handles
     * @var array
     */
    private $handles = [
        'env'   =>  'Frame\Handles\Env',
        'config'=>  'Frame\Handles\Config',
        'error' =>  'Frame\Handles\Merror',
        'exception' =>  'Frame\Handles\Mexception'
    ];

    /**
     * 容器绑定
     * @var array
     */
    private $binds = [
        'request'   => Request::class,
        'response'  => Response::class
    ];

    /**
     *
     */
    public function __construct()
    {
        foreach ($this->handles as $handle) {
            (new $handle())->register();
        }
        static::setInstance($this);
        foreach ($this->binds as $bind) {
            $this->instances[$bind] = (new $bind());
        }
        $uri = $this->instances[Request::class]->uri;
        $routeObj = new Route($uri);
        $class = $routeObj->dispatch();

        $http = new $class();
        $http->run();
    }
}
