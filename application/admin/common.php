<?php
/**
 * Admin模块公共文件
 * Created by PhpStorm.
 * @author  cbwfree
 */

/**
 * 构建后台管理菜单
 * @return mixed
 */
function build_menus()
{
    $menus = config('admin_menus');

    // TODO 根据权限筛选菜单

    return $menus;
}