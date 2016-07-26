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
use think\Response;
use traits\controller\Jump;

/**
 * Admin 模块 基础控制器
 * Class Base
 * @package app\admin\controller
 */
class Base extends Controller
{
    use Jump;

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

    }

    /**
     * 404 错误页面
     * @return string
     */
    public function _empty()
    {
        return '404 Not Found!!!';
    }

    /**
     * Pjax响应输出
     * @param string $template
     * @param array $vars
     * @param array $replace
     * @param array $config
     * @return Response
     */
    protected function response(string $template = '', array $vars = [], array $replace = [], array $config = []): Response
    {
        $data = $this->fetch($template, $vars, $replace, $config);
        if ($this->request->isPjax()) {
            $result = [
                'code' => 1,
                'msg'  => 'success',
                'time' => $_SERVER['REQUEST_TIME'],
                'data' => $data,
            ];
            return Response::create($result, 'json');
        } else {
            return Response::create($data, 'html');
        }
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