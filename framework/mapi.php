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
// 引入框架类
require(__DIR__ . DS .'Autoload.php');
Autoload::register(dirname(__DIR__) . DS);

$app = new App();
$app->run();
