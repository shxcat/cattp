<?php
/**
 * Admin 模块 基础控制器
 * Created by PhpStorm.
 * @package app\admin\controller
 * @version 16/7/21 下午2:31
 * @author  cbwfree
 */
namespace app\admin\controller;

use think\Controller;
use think\Loader;
use think\Response;

/**
 * Admin 模块 基础控制器
 * Class Base
 * @package app\admin\controller
 */
class Base extends Controller
{
    const SAVE_INSERT = 'insert';
    const SAVE_UPDATE = 'update';

    /**
     * 用户ID
     * @access protected
     * @var int
     */
    protected $aid = 0;

    /**
     * @var null|array  用户信息
     */
    protected $user = null;

    /**
     * @var string 跳转地址
     */
    protected $goto = '';

    /**
     * TP控制器初始化
     */
    protected function _initialize()
    {
        // 二级控制器初始化
        if (method_exists($this, '_init')) {
            $this->_init();
        }

        // 三级控制器初始化
        if (method_exists($this, '_exec')) {
            $this->_exec();
        }

        if ($this->request->isPost()) {

        }

        $this->goto = $this->request->get('goto', '');
        $this->assign('goto', urlencode($this->goto));
    }

    /**
     * 404 错误页面
     * @return string
     */
    public function _empty()
    {
        return $this->fetch('public/error');
    }

    /**
     * 检查是否登录
     * @return bool
     */
    protected function _isLogin()
    {

        return true;
    }
}