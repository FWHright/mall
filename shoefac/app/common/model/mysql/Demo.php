<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2020/6/23
 * Time: 15:41
 */

namespace app\common\model\mysql;

use think\model;
class Demo  extends  Model
{
    public  function  getDemoDataByCategory($categoryId,$limit = 10){
        if(empty($categoryId)){
            return [];
        }
        $res =  $this->where("category_id",$categoryId)
            ->limit($limit)
            ->order("id","desc")
            ->select()
            ->toArray();
        return  $res;

    }

}