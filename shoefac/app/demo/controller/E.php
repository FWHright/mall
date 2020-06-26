<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/23
 * Time: 20:17
 */

namespace app\demo\controller;


use app\BaseController;

class E  extends  BaseController
{
    public function  index(){

        throw  new \think\exception\HttpException(400,"找不到数据");
    }
    public function  abc(){
        dump($this->request->type);
    }

}