<?php
/**
 * @Author: jingzhao
 * @Created Time : 2019/09/04 10:13
 * @File Name: app/shop/goods/List.php
 * @Description:
 */
namespace App\Shop\Goods;

use \App\Sql\DBGoodsHelper;

class Lists 
{
    public function run()
    {
        $result = DBGoodsHelper::getGoodsByShop();
        echo json_encode($result);
    }
}
