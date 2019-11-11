<?php
/**
 * @Author: jingzhao
 * @Created Time : 2019/10/09 14:37
 * @File Name: app/sql/DBGoodsHelper.php
 * @Description:
 */
namespace App\Sql;

class DBGoodsHelper extends \Libs\DB\DBModel
{
    /** 
     * @Author: shi jingzhao 
     * @Date: 2019-10-09 14:38:49 
     * @Desc: 获取此店铺商品 
     */
    public static function getGoodsByShop() {
        $sql = "select * from goods where shop_id = 1";
        return self::get($sql);
    }
}
