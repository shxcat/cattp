<?php
/**
 * 后台菜单配置
 * Created by PhpStorm.
 * @package
 * @version 16/7/25 下午3:01
 * @author  cbwfree
 */

return [

    'users'     => [
        'label'     => '管理员管理',
        'icon'      => 'am-icon-user',
        'items'     => [
            ['label' => '管理员列表', 'link' => 'admin/users/lists'],
            ['label' => '新增管理员', 'link' => 'admin/users/add'],
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

];