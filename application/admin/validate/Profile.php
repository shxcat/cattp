<?php
/**
 * 管理员数据校验
 * Created by PhpStorm.
 * @package app\admin\validate
 * @version 16/8/16 下午3:11
 * @author  cbwfree
 */
namespace app\admin\validate;

use think\Validate;

/**
 * 个人信息修改数据校验
 * Class Profile
 * @package app\admin\validate
 */
class Profile extends Validate
{
    /**
     * 验证规则
     * @access protected
     * @var array
     */
    protected $rule = [
        "old_pass"      => "require|length:6,16",
        "new_pass"      => "different:old_pass|length:6,16",
        "realname"      => 'require',
        "mobile"        => 'number|length:11',
        "email"         => "email"
    ];

    /**
     * 验证失败消息
     * @access protected
     * @var array
     */
    protected $message = [
        "old_pass.require"      => "请填写登录密码",
        "old_pass.length"       => "登录密码长度为6~16个字符",
        "new_pass.length"       => "登录密码长度为6~16个字符",
        'new_pass.different'    => '新密码不能与旧密码相同',
        'realname.require'      => '请填写真实姓名',
        'mobile.require'        => '请填写手机号码',
        'mobile.number'         => '手机号码只能为数字',
        'mobile.length'         => '手机号码的长度为11位',
        'email.require'         => '请填写联系邮箱',
        'email.email'           => '邮箱格式不正确',
    ];

    /**
     * 验证场景
     * @access protected
     * @var array
     */
    protected $scene = [

    ];
}