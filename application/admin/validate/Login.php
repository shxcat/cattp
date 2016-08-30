<?php
/**
 * 管理员登录验证器
 * Created by PhpStorm.
 * @package app\admin\validate
 * @version 16/7/22 下午2:30
 * @author  cbwfree
 */
namespace app\admin\validate;

use think\Validate;

/**
 * 管理员登录验证器
 * Class Login
 * @package app\admin\validate
 */
class Login extends Validate
{
    /**
     * 验证规则
     * @access protected
     * @var array
     */
    protected $rule = [
        'username'      => 'require|length:4,20',
        'password'      => 'require|length:4,16',
        'captcha'       => 'require|length:4|captcha:admin_login',
    ];

    /**
     * 验证失败消息
     * @access protected
     * @var array
     */
    protected $message = [
        'username.require'  => '请填写登录帐号',
        'username.length'   => '登录帐号长度为4~20',
        'password.require'  => '请填写登录密码',
        'password.length'   => '登录密码长度为4~16',
        'captcha.require'   => '请填写验证码',
        'captcha.captcha'   => '验证码不正确'
    ];

    /**
     * 验证场景
     * @access protected
     * @var array
     */
    protected $scene = [

    ];
}