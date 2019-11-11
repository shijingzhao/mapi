<?php
namespace Frame;

class Route
{
    /**
     * uri
     * @var string
     */
    private $uri = '';

    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    public function dispatch()
    {
        $class = '\App';
        $uriArr = explode('/', $this->uri);
        $uriArr = array_values(array_filter($uriArr));
        foreach ($uriArr as $val) {
            $class = $class . '\\' . ucfirst($val);
        }
        return $class;
    }
}
