<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2020/6/23
 * Time: 16:07
 */

namespace app\common\business;

use app\common\model\mysql\Demo as DemoModel;
class Demo
{
    /*
     *
     * business 层通过getDemoDataByCategoryId来获取数据
     *
     * */

    public  function  getDemoDataByCategoryId($categoryId,$limit=10){
        $model  = new  DemoModel();
        $results = $model->getDemoDataByCategory($categoryId,$limit);
        if(empty($results)){
            return [];
        }
        $categorys = config("category");
        foreach($results as $key => $result){
            $results[$key]['categoryName'] =$categorys[$results["category_id"]] ?? "其他";
        }
         return $results;
    }

}