<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2020/6/24
 * Time: 16:44
 */

namespace app\admin\controller;


use app\BaseController;
use think\exception\HttpResponseException;

class AdminBase extends BaseController
{
     public  $adminUser = null;
     public  function  initialize()
     {
          parent::initialize();
          //判端是否登录 切换到中间件Auth中
        // if(empty($this->isLogin())){
        //     return $this-> redirect(url("login/index"),302);
         //}
     }
     /*
      * 判端是否登录
      * */
     public  function  isLogin(){
        $this->adminUser =  session(config("admin.session_admin"));
        if(empty($this->adminUser)){
            return false;
        }
        return true;

     }
    public  function  redirect(...$args){
         throw new HttpResponseException(redirect(...$args));
    }
}