<?php
/**
 * 后台菜单配置
 * Created by PhpStorm.
 * @package
 * @version 16/7/25 下午3:01
 * @author  cbwfree
 */

return [

    // 菜单分组
    'group'     => [
        'system'    => [
            'label'     => '系统管理',
            'icon'      => 'am-icon-cog',
            'active'    => true,
        ]
    ],

    // 系统管理菜单
    'menus-system'   => [
        'users'     => [
            'label'     => '管理员管理',
            'icon'      => 'am-icon-user',
            'items'     => [
                ['label' => '管理员列表', 'link' => 'users/lists'],
                ['label' => '新增管理员', 'link' => 'users/add'],
            ]
        ],
    ],

];