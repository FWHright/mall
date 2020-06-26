<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/26
 * Time: 6:38
 */

namespace app\admin\business;
use  app\common\model\mysql\AdminUser  as  AdminUserModel;

class AdminUser
{
    public  $adminUserObj = null;
    public  function __construct()
    {
        $this->adminUserObj  = new AdminUserModel();
    }

    public function  login($data){

            $adminUser = $this->getAdminUserByUsername($data["username"]);
            if(!$adminUser){
                throw new \think\Exception("不存在该用户");
            }

            //判断密码是否正确
            if ($adminUser['password'] != md5($data['password'] . "_singwa_abc")) {

                //return show(config("status.error"), "密码错误");
                throw new \think\Exception("密码错误");
            }

            //需要记录信息到mysql表中
            $updateData = [
                "last_login_time" => time(),
                "last_login_ip" => request()->ip(),
                //"update_time" => time()
            ];
            $res = $this->adminUserObj->updateById($adminUser['id'], $updateData);
            if (empty($res)) {
                //return show(config("status.error"), "登录失败");
                throw new \think\Exception("登录失败");
            }

        //记录session
        session(config("admin.session_admin"),$adminUser);
        return true;
    }
    public  function  getAdminUserByUsername($username){

        $adminUser = $this->adminUserObj->getAdminUserByUsername($username);

        if (empty($adminUser) || $adminUser->status != config("status.mysql.table_normal")) {

            return [];
        }
        $adminUser = $adminUser->toArray();
        return $adminUser;
    }
}