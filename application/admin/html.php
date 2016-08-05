<?php
/**
 * Admin模块HTML函数
 * Created by PhpStorm.
 * @author  cbwfree
 */

/**
 * 创建导航条
 * @param array $path       导航条路径
 * @param bool  $tools      导航条工具按钮
 * @return string
 */
function location(array $path = [], $tools = true)
{
    $location = [
        '<ol id="location" class="am-breadcrumb am-breadcrumb-slash">',
        '<li><a href="'.url('admin/index/index').'" class="am-icon-home" data-pjax>首页</a></li>'
    ];
    foreach($path as $key => $val){
        if( is_string($key) ){
            $location[] = '<li><a href="'.url($key).'" data-pjax>'.$val.'</a></li>';
        }else{
            $location[] = '<li>'.$val.'</li>';
        }
    }

    if ($tools) {
        $location[] = '<li class="am-fr"><a href="javascript:;" onclick="$.AMUI.pjax.reload();" title="刷新"><i class="am-icon-refresh"></i>刷新(F5)</a></li>';
        $location[] = '<li class="am-fr"><a href="javascript:;" onclick="window.open($.AMUI.pjax.location());" title="新窗口打开"><i class="am-icon-external-link"></i>新窗口打开</a></li>';
    }

    $location[] = '</ol>';
    return implode("", $location);
}

/**
 * 返回 Tree 缩进信息
 * @param int    $level     缩进等级
 * @param string $space     缩进空白
 * @param string $symbol    缩进符号
 * @return string
 */
function tree_indent($level = 0, $space = '&nbsp;&nbsp;&nbsp;&nbsp;', $symbol = "⊦")
{
    return $level > 0 ? str_repeat($space, $level) . $symbol. " " : '';
}

/**
 * 获取 Select 选中值
 * @param string $data      原数据
 * @param string $value     匹配值
 * @return string
 */
function check_selected($data, $value = '')
{
    return (! is_null($value) && $value != '' && $value == $data) ? ' selected' : '';
}

/**
 * 获取 checkbox/radio 选中值
 * @param string $data      原数据
 * @param string $value     匹配值
 * @return string
 */
function check_checked($data, $value = '')
{
    return (! is_null($value) && $value != '' && $value == $data) ? ' checked' : '';
}