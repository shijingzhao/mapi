<?php
/**
 * @Author: jingzhao
 * @Created Time : 2019/08/22 20:24
 * @File Name: Http.php
 * @Description:
 */
namespace FrameWork;

class Http
{
    /**
     * 系统实例
     * @var object
     */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * 执行应用程序
     * @param
     * @return  Response
     */
    public function run()
    {
        // 创建request对象
        $request = $this->app->make('request', [], true);
        $this->app->instance('request', $request);

        try {
            $response = $this->execRequest($request);
            var_dump($response);
        }
        catch (Throwable $e) {

        }
    }

    /**
     * 执行请求
     * @param   Request $request
     * @return  mixed
     */
    protected function execRequest(Request $request)
    {
        var_dump($request);die;
    }
}
