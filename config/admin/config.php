<?php
/**
 * Admin 模块配置
 * Created by PhpStorm.
 * @author  cbwfree
 */

$config = [
    'template'  => [
        'taglib_pre_load'   => 'app\\admin\\taglib\\Page,app\\admin\\taglib\\Form'
    ],
];

// 后台菜单配置
$config['admin_menus'] = include __DIR__ . '/menus.php';

return $config;