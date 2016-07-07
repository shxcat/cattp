<?php
/**
 * 应用入口
 * Created by PhpStorm.
 * @package void
 * @version 16/3/16 下午5:13
 * @author cbwfree
 */
// 定义环境版本
define("PHP_ENV_VER", "5.5.0");

// 检测PHP环境
if (version_compare(PHP_VERSION, PHP_ENV_VER, '<')) {
    die('require PHP > '.PHP_ENV_VER.' !');
}

// 由于 PHP5.6 的版本弃用了 $HTTP_RAW_POST_DATA 特性, 需要设置php.ini
//ini_set("always_populate_raw_post_data", -1);

define('APP_PATH',          __DIR__ . '/../application/');          // 定义应用目录
define('RUNTIME_PATH',      __DIR__ . "/../runtime/");              // 定义运行时目录

/* 加载引导文件 */
include __DIR__ . '/../framework/start.php';
