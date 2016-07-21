<?php
/**
 * 应用入口
 * Created by PhpStorm.
 * @package void
 * @version 16/3/16 下午5:13
 * @author cbwfree
 */
// 定义环境版本
define("PHP_ENV_VER", "7.0.0");

// 检测PHP环境
if (version_compare(PHP_VERSION, PHP_ENV_VER, '<')) {
    die('require PHP > '.PHP_ENV_VER.' !');
}

define('APP_PATH',          __DIR__ . '/../application/');          // 定义应用目录
define('RUNTIME_PATH',      __DIR__ . '/../runtime/');              // 定义运行时目录
define('CONF_PATH',         __DIR__ . '/../config/');               // 定义配置文件目录

/* 加载引导文件 */
include __DIR__ . '/../framework/start.php';

