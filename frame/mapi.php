<?php
namespace Frame;

require_once __DIR__ . DS . 'autoload.php';
Autoload::register(dirname(__DIR__));

try {
    $app = new App();
} catch (\Exception $e) {
    var_dump($e);
}
