<?php
/**
 * 后台菜单配置
 * Created by PhpStorm.
 * @package
 * @version 16/7/25 下午3:01
 * @author  cbwfree
 */

return [

    // 默认不启用分组菜单
    'group_switch'  => true,

    // 菜单分组
    'group'     => [
        'main'      => [
            'label'     => '主菜单',
            'icon'      => 'am-icon-tachometer',
            'group'     => 'menus-main'
        ],
        'system'    => [
            'label'     => '系统管理',
            'icon'      => 'am-icon-cog',
            'group'     => 'menus-system'
        ]
    ],

    // 主菜单列表
    'menus-main'    => [

    ],

    // 系统管理
    'menus-system'  =>[
        'users'     => [
            'label'     => '管理员管理',
            'icon'      => 'am-icon-user',
            'items'     => [
                ['label' => '管理员列表', 'link' => 'admin/admins/lists'],
                ['label' => '新增管理员', 'link' => 'admin/admins/add'],
            ]
        ],
        'access'     => [
            'label'     => '权限管理',
            'icon'      => 'am-icon-key',
            'items'     => [
                ['label' => '授权列表', 'link' => 'admin/access/lists'],
                ['label' => '权限分组', 'link' => 'admin/access/group'],
                ['label' => '权限节点', 'link' => 'admin/access/nodes'],
            ]
        ],
        'setting'     => [
            'label'     => '系统管理',
            'icon'      => 'am-icon-cog',
            'items'     => [
                ['label' => '站点设置', 'link' => 'admin/setting/site'],
            ]
        ],
    ],

];