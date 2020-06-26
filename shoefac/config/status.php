<?php
/**
 * 该文件主要存放业务状态码配置
 * Created by PhpStorm.
 * User: PC
 * Date: 2020/6/23
 * Time: 13:42
 */
return [
    "success" => 1,
    "error" => 0,
    "not_login" => -1,
    "user_is_register" => -2,
    "action_not_found" => -3,
    "controller_not_found" =>-4,
    //mysql相关状态配置
    "mysql" =>[
        "table_normal" => 1, //正常
        "table_pedding" =>0, //待审核
        "table_delete" =>99 //删除
    ],



];