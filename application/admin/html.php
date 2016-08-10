<?php
/**
 * Admin模块HTML函数
 * Created by PhpStorm.
 * @author  cbwfree
 */

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