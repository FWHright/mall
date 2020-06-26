<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2020/6/24
 * Time: 9:31
 */

namespace app\admin\controller;

use think\captcha\facade\Captcha;

class Verify
{
   public function  index(){
       return Captcha::create("verify");
   }
}