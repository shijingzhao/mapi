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

class App extends \FrameWork\Container
{

    /**
     * 框架路径
     * @var array
     */
    protected $path = [
        'root'      => '',
        'log'       => '',
        'app'       => '',
        'framework' => '',
    ];

    /**
     * 容器绑定标识
     */
    protected $bind = [
        'app'       => App::class,
        'exception' => Exception::class,
        'request'   => Request::class,
        'response'  => Response::class,
    ];

    public function __construct()
    {
        $this->path = [
            'root'      => dirname(__DIR__) . DS,
            'log'       => DS . 'var' . DS . 'mapilog' . DS,
            'app'       => dirname(__DIR__) . DS . 'app',
            'framework' => __DIR__ . DS,
        ];

        static::setInstance($this);

        $this->instance('app', $this);
        $this->instance('FrameWork\Container', $this);

        foreach ($this->bind as $handle) {
            $this->make($handle);
        }
    }

    public function run()
    {
        try {
            throw new Exception('msg', 0);
        }
        catch (Exception $e) {
            $this->response->exceptionHandler($e);
        }
    }
}
