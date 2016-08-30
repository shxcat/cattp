<?php
/**
 * 全局URL路由配置
 * Created by PhpStorm.
 * @author  cbwfree
 */

return [

    // admin 模块路由
    'admin/index'       => 'admin/index/index',         // 后台首页
    'admin/dashboard'   => 'admin/index/dashboard',     // 仪表盘
    'admin/signin'      => 'admin/login/index',         // 登录页面
    'admin/signout'     => 'admin/login/logout',        // 登出页面

];
