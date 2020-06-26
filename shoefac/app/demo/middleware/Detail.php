<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/23
 * Time: 20:15
 */

namespace app\demo\middleware;


class Detail
{
   public  function  handle($requset,\Closure $next){

     $requset->type = "demo-detail";
     return $next($requset);
   }
   /*
    * 中间件结束调度
    *
    * */
   public function end(\think\Response $response){

   }
}