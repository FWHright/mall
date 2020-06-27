<?php
namespace org;
/**
 * Created by PhpStorm.
 * User: junyv
 * Date: 2016/9/18
 * Time: 10:04
 */

class Role
{
    public static function getmanagemenu($admin=array()){
        if(!empty($admin)){
            $roleid = $admin['roleid'];
            session('roleid',$roleid);
        }else{
            $roleid = session('roleid');
        }
        $obj = $roleid == config('SUPERADMIN_ROLEID') ? db('menu') : db('role_menu');
        //缓存权限菜单
        $where = $roleid == config('SUPERADMIN_ROLEID') ? '1=1' : array('roleid'=>$roleid);
       // $menu = cache('managemenu'.$roleid);
        //if(!$menu){
            $menu = $obj->where($where)->order('ordid asc')->select();
            cache('managemenu'.$roleid,objToArray($menu));
       // }
        return $menu;
    }
    public static function getTopMenu($info=''){
        $menu = self::getmanagemenu($info);
        $arr  = array();
        foreach($menu as $k=>$v){
            if($v['pid']==0 && $v['display']==1){
                $arr[] = $v;
            }
        }
        return $arr;
    }
    public static function getSubMenu($pid,$with_self=false){
        $menu = self::getmanagemenu();
        $subarr = array();
        foreach($menu as $k=>$v){
            if(($v['pid']==$pid && $v['display']==1) || ($with_self ? $v['menuid']==$pid : '')){
                $subarr[] = $v;
            }
        }
        return $subarr;
    }

    /**
     * @param string $c 控制器名称
     * @param string $a 方法名称
     * @param string $condigiotn 验证条件
     * @return bool
     */
    public static function check($c='',$a='',$conditiotn=array()){
        $rights = self::getmanagemenu();
        $flag   = FALSE;
        //如果是超级管理员 将不进行任何验证
        if(session('roleid') == config('SUPERADMIN_ROLEID')) return TRUE;
        $no_auth_controler = explode('|',strtolower(config('NOT_AUTH_MODULE')));
        $no_auth_action    = explode('|',strtolower(config('NOT_AUTH_ACTION')));
        if(in_array(strtolower($c),$no_auth_controler)) return TRUE;
        foreach($no_auth_action as $action){
            if(FALSE !== strpos(strtolower($a),$action)){
                return TRUE;
            }
        }
        if($conditiotn){
            //TODO 目前只根据角色id判断
            //如果给定了条件并且角色id为超管或为自己时不能执行删除和设置操作
            if(is_array($conditiotn)){
                $role_id = $conditiotn['role_id'];
                if($role_id == config('SUPERADMIN_ROLEID') || $role_id == session('roleid')){
                    return FALSE;
                }
            }
        }else{
            foreach($rights as $v){
                if(strtolower($v['module_name'])==strtolower($c) && strtolower($v['action_name'])==strtolower($a)){
                    return TRUE;
                }
            }
        }
        return $flag;
    }
}