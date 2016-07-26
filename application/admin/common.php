<?php
/**
 * Admin模块公共文件
 * Created by PhpStorm.
 * @author  cbwfree
 */
// 定义行为
\think\Hook::add('app_end', function(&$data) {
    if (\think\Request::instance()->isPjax()) {
        if ($data instanceof \think\Response) {
            $data = $data->getData();
        }

        if (!isset($data['code'])) {
            $data = [
                'code' => 1,
                'msg'  => '',
                'time' => $_SERVER['REQUEST_TIME'],
                'data' => $data,
            ];
        }

        $data = \think\Response::create($data, 'json');
    }
});


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