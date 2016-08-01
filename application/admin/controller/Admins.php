<?php
/**
 * 管理员管理控制器
 * Created by PhpStorm.
 * @package app\admin\controller
 * @version 16/7/29 下午5:30
 * @author  cbwfree
 */
namespace app\admin\controller;

/**
 * 管理员管理控制器
 * Class Admins
 * @package app\admin\controller
 */
class Admins extends Auth
{

    /**
     * 管理员列表
     * @return mixed
     */
    public function lists()
    {

        return $this->fetch();
    }

    /**
     * 新增管理员
     * @return mixed
     */
    public function add()
    {

        return $this->fetch();
    }

}