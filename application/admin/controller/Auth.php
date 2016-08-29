<?php
/**
 * Admin 模块 权限认证控制器
 * Created by PhpStorm.
 * @package app\admin\controller
 * @version 16/7/21 下午5:16
 * @author  cbwfree
 */
namespace app\admin\controller;

/**
 * Admin 模块 权限认证控制器
 * Class Auth
 * @package app\admin\controller
 */
class Auth extends Base
{

    /**
     * 初始化, 进行权限检查
     * 除登录控制器以外, 其余控制器均继承此控制器
     */
    public function _init()
    {
        // 未登录
        if (empty($this->admin)) {
            // 如果为 Ajax (包含Pjax) 请求则返回JSON消息
            if ($this->request->isAjax()) {
                $this->result(url('admin/login/index'), 100, '您的登录已失效, 请重新登录 ...');
            } else {
                // 否则直接跳转登录页面
                $this->redirect(url('admin/login/index'));
            }
        }
    }

}