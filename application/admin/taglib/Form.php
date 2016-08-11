<?php
/**
 * 表单模版标签库
 * Created by PhpStorm.
 * @package app\admin\taglib
 * @version 16/8/1 下午5:09
 * @author  cbwfree
 */
namespace app\admin\taglib;

use think\Request;
use think\template\TagLib;

/**
 * 表单模版标签库
 * Class Form
 * @package app\admin\taglib
 */
class Form extends TagLib
{
    /**
     * @var array   定义标签列表
     *              标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
     */
    protected $tags = [
        'form'      => ['attr' => 'submit,class', 'expression' => true, 'level' => 0],
        'fieldset'  => ['attr' => 'class,title', 'expression' => true, 'level' => 0],
        'input'     => ['attr' => 'label,name,type,value,class,width,tips,help,require', 'close' => 0],
        'select'    => [],
        'text'      => [],
        'checkbox'  => [],
        'radio'     => [],
    ];

    /**
     * 创建表单
     * @param $tag
     * @param $content
     * @return string
     */
    public function tagForm($tag, $content)
    {
        $submit = isset($tag['submit']) ? $tag['submit'] : '';
        $class  = isset($tag['class']) ? $tag['class'] : '';

        if (empty($submit)) {
            $submit = url(Request::instance()->action());
        } else {
            $submit = url($submit);
        }

        $parse = '<form action="'.$submit.'" class="am-form am-form-horizontal am-form-xs '.$class.'">';
        $parse.= $content;
        $parse.= '<div class="am-form-group">';
        $parse.= '<div class="am-u-sm-8 am-u-md-10 am-u-sm-offset-4 am-u-md-offset-2">';
        $parse.= '<button type="submit" class="am-btn am-btn-primary"><i class="am-icon-save"></i> 保存数据</button>&nbsp;&nbsp;';
        $parse.= '<button type="reset" class="am-btn am-btn-default"><i class="am-icon-repeat"></i> 重置表单</button>';
        $parse.= '</div></div></form>';

        return $parse;
    }

    /**
     * 表单 Fieldset
     * @param $tag
     * @param $content
     * @return string
     */
    public function tagFieldset($tag, $content)
    {
        $class  = isset($tag['class']) ? ' class="'.$tag['class'].'"' : '';
        $title  = isset($tag['title']) ? $tag['title'] : '';

        $parse = '<fieldset'.$class.'>';

        if ($title) {
            $parse.= '<legend><i class="am-icon-bars"></i> '.$title.'</legend>';
        }

        $parse.= $content;
        $parse.= '</fieldset>';
        return $parse;
    }

    /**
     * 创建输入框
     * @param $tag
     * @return string
     */
    public function tagInput($tag)
    {
        $type   = isset($tag['type']) ? $tag['type'] : 'text';
        $value  = isset($tag['value']) ? $tag['value'] : '';
        $class  = isset($tag['class']) ? $tag['class'] : '';
        $width  = isset($tag['width']) ? intval($tag['width']) : 5;
        $tips   = isset($tag['tips']) ? $tag['tips'] : '';
        $help   = isset($tag['help']) ? $tag['help'] : '';
        $require= isset($tag['require']) ? ' require' : '';

        if ($width <= 0) {
            $width = 5;
        } elseif ($width > 10) {
            $width = 5;
        }

        $help_width = 10 - $width;

        if (empty($help) && $width < 10) {
            $end = ' am-u-end';
        } else {
            $end = '';
        }

        // 支持用函数传数组
        if (strpos($value, ":") === 0) {
            $var = '$_' . uniqid();
            $value = '<?php ' . $var . '=' . $this->autoBuildVar($value) . '; echo '.$var.'; ?>';
        } elseif (strpos($value, "$") === 0) {
            $value = '<?php echo '.$this->autoBuildVar($value).'; ?>';
        }

        $parse = '<div class="am-form-group">';
        $parse.= '<label class="am-u-sm-4 am-u-md-2 am-form-label'.$require.'">'.$tag['label'].'</label>';
        $parse.= '<div class="am-u-sm-8 am-u-md-'.$width.$end.'">';
        $parse.= '<input type="'.$type.'" name="'.$tag['name'].'" value="'.$value.'" class="am-form-field '.$class.'" placeholder="'.$tips.'">';
        $parse.= '</div>';

        if ($help && $help_width) {
            $parse.= '<div class="am-hide-sm-only am-u-md-'.$help_width.' am-form-help">'.$help.'</div>';
        }

        $parse.= '</div>';

        return $parse;
    }

}