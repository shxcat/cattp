<?php
/**
 * Admins 数据模型
 * Created by PhpStorm.
 * @package app\admin\model
 * @version 16/8/17 下午3:15
 * @author  cbwfree
 */
namespace app\admin\model;

use think\Model;

/**
 * Admins 数据模型
 * Class Admins
 * @package app\admin\model
 */
class Admins extends Model
{

    protected $auto = ['salt', 'password'];
    protected $insert = ['add_time', 'login_time', 'last_time'];


    protected function setSaltAttr($value, $data)
    {
        return mt_salt();
    }

}