<?php
namespace FrameWork;

class Request
{
    /**
     * 域名
     * @var string
     */
    protected $host = '';

    /**
     * URL
     * @var string
     */
    protected $url = '';

    /**
     * IP
     * @var string
     */
    protected $ip = '';

    /**
     * 请求类型
     * @var string
     */
    protected $method = '';

    /**
     * FILE
     * @var array
     */
    protected $file = [];

    /**
     * SESSION
     * @var
     */
    protected $session;

    /**
     * COOKIE
     * @var
     */
    protected $cookie;

    /**
     * server参数
     * @var array
     */
    protected $server = [];

    /**
     * header参数
     * @var array
     */
    protected $header = [];

    /**
     * 请求参数
     * @var array
     */
    protected $param = [];

    public function __construct()
    {
        $this->server = $_SERVER;
        $this->cookie = $_COOKIE;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->file   = $_FILES ?? [];
        $this->header = $this->getHeader();
        $this->param  = $this->getParam();
        var_dump($_SESSION);die;
    }

    /**
     * header 头
     * @param   array   $header 头部信息
     * @return  array
     */
    private function getHeader($header = [])
    {
        foreach ($this->server as $key => $val) {
            if (0 === strpos($key, 'HTTP_')) {
                $key            = str_replace('_', '-', strtolower(substr($key, 5)));
                $header[$key]   = $val;
            }
        }

        return $header;
    }

    /**
     * param 请求参数
     * @param   array|json  $_POST|$_GET
     * @return
     */
    public function getParam()
    {
        if (isset($this->header['content-type']) &&
            $this->header['content-type'] == 'application/json') {
            $data = file_get_contents('php://input');
            $data = json_decode($data, true);
        }
        elseif ($this->method == 'GET') {
            $data = $_GET;
        }
        elseif ($this->method == 'POST') {
            $data = $_POST;
        }
        else {
            $data = [];
        }
        return $this->filterData($data);
    }

    /**
     * 参数过滤转义
     * @param   array   $data
     * @return  array
     */
    public function filterData($data)
    {
        return $data;
    }
}
