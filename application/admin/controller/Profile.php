<?php
/**
 * Admin 个人信息
 * Created by PhpStorm.
 * @package app\admin\controller
 * @version 16/7/21 下午5:15
 * @author  cbwfree
 */
namespace app\admin\controller;

use app\admin\model\Admins as AdminsModel;

/**
 * Admin 个人信息
 * Class Profile
 * @package app\admin\controller
 */
class Profile extends Auth
{
    /**
     * 个人信息
     * @return mixed
     */
    public function info()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $code = $this->validate($data, 'Profile');
            if ($code !== true) {
                $this->error($code);
            }

            $profile = AdminsModel::get($this->aid);
            $old_pass = gen_password($data['old_pass'], $profile->salt);
            if ($old_pass != $profile->password) {
                $this->error("登录密码错误，不允许修改信息");
            }

            if (! empty($data['new_pass'])) {
                $data['salt'] = mt_salt();
                $data['password'] = gen_password($data['new_pass'], $data['salt']);
            }

            unset($data['old_pass']);
            unset($data['new_pass']);

            $result = $profile->allowField(true)->save($data);
            if ($result === false) {
                $this->error($profile->getError());
            }

            $this->success("个人资料修改成功！");
        }

        $this->assign("gender", AdminsModel::$attr['gender']);
        $this->assign("status", AdminsModel::$attr['status']);
        return $this->fetch();
    }
}