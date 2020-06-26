<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/23
 * Time: 21:48
 */

namespace app\admin\controller;


use app\BaseController;
use think\facade\View;

class Login extends  AdminBase
{
    public  function  initialize()
    {
        if($this->isLogin()){
            return $this->redirect(url("index/index"));
        }
    }

    public function  index(){

        return View::fetch();
    }
    public function  md5(){

        //admin admin
        halt(session(config("admin.session_admin")));
        echo  md5("admin_singwa_abc");
    }
   public  function  check(){
        if(!$this->request->isPost()){
            return show(config("status.error"),"请求方式错误");
        }
        //参数校验
       $username= $this->request->param("username","","trim");
       $password= $this->request->param("password","","trim");
       $captcha= $this->request->param("captcha","","trim");
       $data = [
           'username' => $username,
           'password' => $password,
           'captcha' => $captcha
       ];
       $validate = new  \app\admin\validate\AdminUser();
       if(!$validate->check($data)){
           return show(config("status.error"),$validate->getError());
       }
       try{
       $adminUserObj = new \app\admin\business\AdminUser();
       $result =  $adminUserObj->login($data);
       }catch(\Exception $e) {
           return show(config("status.error"),$e->getMessage());
       }
       if($result){
           return show(config("status.error"),"登录成功");
       }
       return show(config("status.error"),$validate->getError());
}
}