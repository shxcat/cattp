<?php
/**
 * Index 模块基础控制器
 * Created by PhpStorm.
 * @package app\index\controller
 * @version 16/7/21 下午5:12
 * @author  cbwfree
 */
namespace app\index\controller;

use think\Controller;
use traits\controller\Jump;

/**
 * Index 模块基础控制器
 * Class Base
 * @package app\index\controller
 */
class Base extends Controller
{
    use Jump;

    /**
     * TP控制器初始化
     */
    public function _initialize()
    {
        // 二级控制器初始化
        if (method_exists($this, '_init')) {
            $this->_init();
        }

        // 三级控制器初始化
        if (method_exists($this, '_exec')) {
            $this->_exec();
        }


    }
}