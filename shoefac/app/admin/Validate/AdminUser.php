<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/26
 * Time: 6:06
 */

namespace app\admin\Validate;


use think\Validate;

class AdminUser  extends Validate
{
   protected   $rule =[
       'username' => 'require',
       'password' => 'require',
       'captcha' => 'require|checkCapcha',
   ];
   protected  $message =[
       'username' => '用户名必须',
       'password' => '密码必须',
       'captcha' => '验证码必须',
   ];
   protected  function  checkCapcha($value,$rule,$data){
      if(!captcha_check($value)){
          return "您输入的验证码不正确";
      }
          return  true;
   }
}