<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2020/6/24
 * Time: 10:07
 */

namespace app\admin\controller;


use app\BaseController;
use think\facade\View;

class Index  extends  AdminBase
{
    public  function  index(){
        return View::fetch();
    }
    public  function welcome(){
        return View::fetch();
    }
}