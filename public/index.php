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
error_reporting(E_ALL);
/**
 * 定义操作系统兼容常量
 * 1. DS 目录分隔符
 */
define('DS', DIRECTORY_SEPARATOR);


/**
 * 加载框架执行文件
 */
require(dirname(__DIR__) . DS . 'frame' . DS . 'mapi.php');
