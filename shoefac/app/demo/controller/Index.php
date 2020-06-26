<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2020/6/23
 * Time: 15:03
 */

namespace app\demo\controller;


use app\BaseController;
use app\common\business\Demo;

class Index   extends  BaseController
{
    public function  index(){
        $categoryId = $this-> request->param("category_id",0,"intval");
        if(empty($categoryId)){
            return show(config("status.error"),"参数错误");
        }
        $demo = new Demo();
        $res = $demo->getDemoDataByCategoryId($categoryId);
        return  show(config("status.success"),"ok",$res);
    }
    public function hello(){
        return 'dsadf';
    }
    public  function  abc(){
        return '123456';
    }

}