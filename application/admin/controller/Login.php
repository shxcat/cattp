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
use think\captcha\Captcha;
use think\Config;

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
        return $this->fetch();
    }

    /**
     * 验证码
     * @return \think\Response
     */
    public function captcha()
    {
        $captcha = new Captcha(Config::get('captcha'));
        return $captcha->entry('admin_login');
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
        if( ! $admin ){
            $this->error("用户不存在");
        }

        // 密码检查
        $password = gen_password($post['password'], $admin->salt);
        if ($password !== $admin->password) {
            $this->error('登录密码错误');
        }

        // 帐号状态检查
        if (! $admin->status) {
            $this->error("账户已被锁定: " . $admin->remark);
        }

        // 获取登录用户数据
        $login = $admin->getData();
        // 删掉登录用户的敏感信息
        unset($login['password']);
        unset($login['salt']);

        // 登录信息
        $login['login_ip']      = $this->request->ip();
        $login['login_time']    = time();

        $admin->save([
            'login_ip'      => $login['login_ip'],
            'login_time'    => $login['login_time']
        ]);

        // 记录登录信息
        session(LOGIN_ADMIN, $login);

        $this->success('登录成功', url('admin/index/index'));
    }

    /**
     * 安全退出
     */
    public function logout()
    {
        session(LOGIN_ADMIN, null);
        $this->redirect(url('admin/login/index'));
    }
}