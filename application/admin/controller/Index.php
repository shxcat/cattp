<?php
/**
 * Admin 模块首页
 * Created by PhpStorm.
 * @package app\admin\controller
 * @version 16/7/21 下午5:15
 * @author  cbwfree
 */
namespace app\admin\controller;

/**
 * Admin 模块首页
 * Class Index
 * @package app\admin\controller
 */
class Index extends Auth
{
    /**
     * 首页内容
     */
    public function index()
    {
        return $this->fetch();
    }
}