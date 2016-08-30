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
                'msg'  => '',
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

/**
 * 生成随机字符串
 * @param int    $length
 * @param string $chars
 * @return string
 */
function mt_salt($length = 6, $chars = '')
{
    $hash = '';
    if (! $chars) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
    }

    $max = strlen($chars) - 1;

    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }

    return $hash;
}

/**
 * 创建密码签名
 * @param $password
 * @param $salt
 * @return string
 */
function gen_password($password, $salt)
{
    return md5(substr(md5($password), 0, -strlen($salt)) . $salt);
}