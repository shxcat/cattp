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
 * 管理员数据校验
 * Class Admins
 * @package app\admin\validate
 */
class Admins extends Validate
{
    /**
     * 验证规则
     * @access protected
     * @var array
     */
    protected $rule = [
        "username"      => "require|length:5,20|alphaDash|unique:admins",
        "password"      => "requireIf:id,|length:6,16",
        "realname"      => 'require',
        "mobile"        => 'number|length:11',
        "email"         => "email",
        "remark"        => 'requireIf:status,0',
    ];

    /**
     * 验证失败消息
     * @access protected
     * @var array
     */
    protected $message = [
        "username.require"      => "请填写用户名称",
        "username.length"       => "用户名长度为5~20个字符",
        "username.alphaDash"    => "用户名只能为数字,字母,下划线",
        "username.unique"       => "用户名已存在",
        "password.requireIf"    => "请填写登录密码",
        "password.length"       => "登录密码长度为6~24个字符",
        'realname.require'      => '请填写真实姓名',
        'mobile.require'        => '请填写手机号码',
        'mobile.number'         => '手机号码只能为数字',
        'mobile.length'         => '手机号码的长度为11位',
        'email.require'         => '请填写联系邮箱',
        'email.email'           => '邮箱格式不正确',
        'remark'                => '账户锁定必须填写锁定原因',
    ];

    /**
     * 验证场景
     * @access protected
     * @var array
     */
    protected $scene = [

    ];
}