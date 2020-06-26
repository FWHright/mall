<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2020/6/23
 * Time: 11:46
 */

namespace app\controller;


class Error
{
    public  function  __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        //dump($name);
        //dump($arguments);
        //如果是API接口的，返回json数据，如果是模板引擎，则返回错误、
//        $res = [
//            'status' => 0,
//            'massage' =>'找不到该控制器',
//            'result' =>null
//        ];
//        return json($res,400);
        return show(config("status.controller_not_found"),"找不到{$name}控制器",null,400);
    }

}