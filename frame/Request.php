<?php
namespace Frame;

class Request
{
    /**
     * beginTime
     * @var string
     */
    public $beginTime = '';

    /**
     * endTime
     * @var string
     */
    public $endTime = '';

    /**
     * host
     * @var string
     */
    public $host = '';

    /**
     * url
     * @var string
     */
    public $uri = '';

    /**
     * ip
     * @var string
     */
    public $ip = '';

    /**
     * method
     * @var string
     */
    public $method = '';

    /**
     * params
     * @var array
     */
    public $params = [];

    /**
     * files
     * @var array
     */
    public $files = [];

    /**
     * session
     * @var
     */
    public $session = [];

    /**
     * cookie
     * @var
     */
    public $cookie = [];

    /**
     * server
     * @var
     */
    public $server = [];

    /**
     * header
     * @var
     */
    public $header = [];


    public function __construct()
    {
        $this->cookie = $_COOKIE;
        $this->server = $_SERVER;
        $this->method = $this->server['REQUEST_METHOD'];
        $this->header = $this->getHeader();
        $this->params = $this->getRequest();
        $this->ip     = $this->getIp();
        $this->uri    = $this->getUri();
        $this->host   = $this->header['HOST'];
        $this->files  = $this->getFiles();

        return $this;
    }

    private function getHeader($headers = [])
    {
        foreach ($this->server as $key => $value) {
            if (substr($key, 0, 5) != 'HTTP_') continue;

            $k = str_replace('_', '-', substr($key, 5));
            $headers[$k] = $value;
            unset($this->server[$key]);
        }
        return $headers;
    }

    private function getRequest()
    {
        switch ($this->method) {
            case 'GET':
                $this->params = $_GET;
                break;

            case 'POST':
                $this->params = $_POST;
                break;

            default:
                return [];
        }

        return $this->filter($this->params);
    }

    private function filter($params)
    {
        return $params;
    }

    private function getIp()
    {
        if(isset($this->server['HTTP_CLIENT_IP']) &&
            !empty($this->server['HTTP_CLIENT_IP'])
        ) {
            $ip = $this->server['HTTP_CLIENT_IP'];
        }
        elseif (isset($this->server['HTTP_X_FORWARDED_FOR']) &&
            !empty($this->server['HTTP_X_FORWARDED_FOR'])
        ) {
            $ip = $this->server['HTTP_X_FORWARDED_FOR'];
        }
        else {
            $ip = $this->server['REMOTE_ADDR'];
        }

        return $ip;
    }

    private function getUri()
    {
        $uri = explode('?', $this->server['REQUEST_URI']);
        return isset($uri[0]) ? $uri[0] : '';
    }

    private function getFiles()
    {
        return $_FILES;
    }
}
