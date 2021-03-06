<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2020/6/24
 * Time: 14:21
 */

namespace app\common\model\mysql;


use think\Model;

class AdminUser extends  Model
{
    /*
     *
     * 根据用户名获取后端表的数据
     * */
    public  function  getAdminUserByUsername($username){
    if(empty($username)){
        return false;
    }
     $where =[
         "username" => trim($username),
     ];
    $result = $this->where($where)->find();
    return $result;
    }
    /*
     * 根据主键ID更新数据表中的数据
     * */
    public function  updateById($id,$data){
      $id = intval($id);
      if(empty($id) || empty($data) || !is_array($data)){
          return false;
      }
      $where = [
          "id" => $id,
      ];
      return $this->where($where)->save($data);
    }

}