<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2020/6/24
 * Time: 17:13
 */

namespace app\admin\controller;


class Logout  extends  AdminBase
{
    public  function  index(){
        //清除session
        session(config("admin.session_admin"),null);
        return  redirect(url("login/index"));
    }

}