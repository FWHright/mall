<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/23
 * Time: 20:17
 */

namespace app\demo\controller;


use app\BaseController;

class Detail  extends  BaseController
{
    public function  index(){

        dump($this->request->type);
    }


}