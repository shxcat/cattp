<?php
/**
 * 登录控制器
 * Created by PhpStorm.
 * @package app\admin\controller
 * @version 16/7/22 上午11:10
 * @author  cbwfree
 */
namespace app\admin\controller;

use app\admin\model\Admins as AdminsModel;

/**
 * 登录控制器
 * Class Login
 * @package app\admin\controller
 */
class Login extends Base
{
    /**
     * 登录页面
     * @return mixed
     */
    public function index()
    {
        // 如果已登录, 则跳转至首页
        if (! empty($this->admin)) {
            $this->redirect(url('admin/index/index'));
        }

        $goto = $this->request->get('goto', '');

        $this->assign('goto', $goto);
        $this->assign('captcha', 'admin_login');
        return $this->fetch();
    }

    /**
     * 登录检查
     * @throws \think\Exception
     */
    public function check()
    {
        $post = $this->request->post();

        // 数据校验
        $result = $this->validate($post, 'Login');
        if( $result !== true ){
            $this->error($result);
        }

        // 获取用户信息
        $admin = AdminsModel::where('username', $post['username'])->find();
        $user = db("admin")->where('username', $post['username'])->find();
        if( ! $user ){
            $this->error("管理员用户不存在");
        }

        // 密码检查
        if ($user['password']) {
            $this->error("登录密码错误");
        }

        // 状态检查
        if (! $user['status']) {
            $this->error("账户已被锁定" . $user['remark'] ? ': '.$user['remark'] : '');
        }

        // 删掉登录用户的敏感信息
        unset($user['password']);
        unset($user['salt']);

        // 更新管理员登录信息
        $user['login_ip']   = $this->request->ip();
        $user['login_time'] = time();
        db("admin")->where('id', $user['id'])->update([
            "last_ip"       => $user['login_ip'],
            "last_login"    => $user['login_time'],
        ]);

        // 记录登录信息
        session('admin_login_info', $user);

        $this->success('登录成功', url('admin/index/index'));
    }

    /**
     * 安全退出
     */
    public function logout()
    {
        session('admin_login_info', null);
        $this->redirect(url('admin/login/index'));
    }
}