<?php
/**
 * Admin模块公共文件
 * Created by PhpStorm.
 * @author  cbwfree
 */

// 处理Pjax请求
\think\Hook::add('app_end', function(&$data) {
    if (\think\Request::instance()->isPjax()) {
        if ($data instanceof \think\Response) {
            $data = $data->getData();
        }

        if (is_string($data) || ! isset($data['code'])) {
            $data = [
                'code' => 1,
                'msg'  => 'success',
                'time' => $_SERVER['REQUEST_TIME'],
                'data' => $data,
            ];
        }

        $data = \think\Response::create($data, 'json');
    }
});

include __DIR__ . '/html.php';

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