<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 2020/6/23
 * Time: 15:10
 */
use think\facade\Route;

Route::rule("login","login/check","post")->middleware(\app\demo\middleware\Check::class);