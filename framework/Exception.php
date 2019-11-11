<?php
namespace FrameWork;

class Exception extends \Exception
{
    /**
     * 异常信息
     * @var array
     */
    private $info = [];

    public function __construct()
    {
        set_exception_handler([$this, 'exceptionHandler']);
    }

    /**
     * 捕获异常
     * @param   object $e   异常
     * @return  void
     */
    public function exceptionHandler($e)
    {
        $this->info = [
            'code'  =>  $e->getCode(),
            'msg'   =>  $e->getMessage(),
            'file'  =>  $e->getFile(),
            'line'  =>  $e->getLine(),
            'trace' =>  $e->getTrace(),
            'previous'  =>  $e->getPrevious()
        ];

        var_dump(__CLASS__);
        var_dump($this->info);die;
    }
}
