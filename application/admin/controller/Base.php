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
use think\Session;

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
     * @var null|array  管理员信息
     */
    protected $admin = null;

    /**
     * @var string 跳转地址
     */
    protected $goto = '';

    /**
     * TP控制器初始化
     */
    protected function _initialize()
    {
        $this->_initAdmin();

        // 二级控制器初始化
        if (method_exists($this, '_init')) {
            $this->_init();
        }

        // 三级控制器初始化
        if (method_exists($this, '_exec')) {
            $this->_exec();
        }
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
     * 初始化用户信息
     */
    protected function _initAdmin()
    {
        $this->admin = Session::get(LOGIN_ADMIN);
        if ($this->admin) {
            $this->aid = $this->admin['id'];
            $this->assign('admin', $this->admin);
        }
    }

    /**
     * 获取表单数据并校验
     * @param       $valid
     * @param array $message
     * @return mixed
     */
    protected function formData($valid, $message = [])
    {
        $data   = $this->request->post();
        $result = $this->validate($data, $valid, $message);

        if ($result !== true) {
            $this->error($result);
        }

        if (isset($data['goto'])) {
            $this->goto = $data['goto'];
        }

        return $data;
    }
}