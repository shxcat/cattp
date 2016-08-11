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
        'label'     => ['attr' => 'label,width,help,require'],
        'input'     => ['attr' => 'label,width,help,require,name,type,value,class,tips,help,attr', 'close' => 0],
        'select'    => ['attr' => 'label,width,help,require,name,options,fields,value,class,first,remote,multi,change,attr', 'close' => 0],
        'text'      => ['attr' => 'label,width,help,require,name,value,class,attr', 'close' => 0],
        'checkbox'  => ['attr' => 'label,width,help,require,name,value,class,attr', 'close' => 0],
        'radio'     => ['attr' => 'label,width,help,require,name,value,class,attr', 'close' => 0],
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
     * 创建Label, 自定义输入框内容
     * @param $tag
     * @param $content
     * @return string
     */
    public function tagLabel($tag, $content)
    {
        // label,width,help,require
        $width  = isset($tag['width']) ? intval($tag['width']) : 5;
        $help   = isset($tag['help']) ? $tag['help'] : '';
        $require= isset($tag['require']) ? ' require' : '';

        if ($width <= 0) {
            $width = 5;
        } elseif ($width > 10) {
            $width = 5;
        }

        if (empty($help) && $width < 10) {
            $end = ' am-u-end';
        } else {
            $end = '';
        }

        $help_width = 10 - $width;

        $parse = '<div class="am-form-group">';
        $parse.= '<label class="am-u-sm-4 am-u-md-2 am-form-label'.$require.'">'.$tag['label'].'</label>';
        $parse.= '<div class="am-u-sm-8 am-u-md-'.$width.$end.'">';
        $parse.= $content;
        $parse.= '</div>';

        if ($help && $help_width) {
            $parse.= '<div class="am-hide-sm-only am-u-md-'.$help_width.' am-form-help">'.$help.'</div>';
        }

        $parse.= '</div>';

        return $parse;
    }

    /**
     * 创建输入框
     * @param $tag
     * @return string
     */
    public function tagInput($tag)
    {
        // name,type,value,class,tips,help,attr
        $name   = $tag['name'];
        $type   = isset($tag['type']) ? $tag['type'] : 'text';
        $value  = isset($tag['value']) ? $tag['value'] : '';
        $class  = isset($tag['class']) ? $tag['class'] : '';
        $tips   = isset($tag['tips']) ? $tag['tips'] : '';
        $require= isset($tag['require']) ? ' require' : '';
        $attr   = isset($tag['attr']) ? ' '.$tag['attr'] : '';

        // 支持用函数传数组
        if (strpos($value, ":") === 0) {
            $var = '$_' . uniqid();
            $value = '<?php ' . $var . '=' . $this->autoBuildVar($value) . '; echo '.$var.'; ?>';
        } elseif (strpos($value, "$") === 0) {
            $value = '<?php echo '.$this->autoBuildVar($value).'; ?>';
        }

        $content = '<input type="'.$type.'" name="'.$name.'" value="'.$value.'" class="am-form-field '.$class.'" placeholder="'.$tips.'"'.$attr.$require.'>';

        return $this->tagLabel($tag, $content);
    }

    /**
     * 创建选择列表
     * @param $tag
     * @return string
     */
    public function tagSelect($tag)
    {
        // name,options,value,class,attr,first
        $name   = $tag['name'];
        $value  = isset($tag['value']) ? $tag['value'] : '';
        $class  = isset($tag['class']) ? $tag['class'] : '';
        $first  = isset($tag['first']) ? $tag['first'] : '';
        $options= isset($tag['options']) ? $tag['options'] : [];
        $remote = isset($tag['remote']) ? $tag['remote'] : '';
        $attr   = isset($tag['attr']) ? ' '.$tag['attr'] : '';
        $require= isset($tag['require']) ? ' require' : '';
        $multi  = isset($tag['multi']) ? ' multiple' : '';
        $change = isset($tag['change']) ? ' onchange="'.$tag['change'].'"' : '';
        $fields = isset($tag['fields']) ? explode(',', $tag['fields']) : '';

        if (strpos($attr, "data-select")) {
            if ($first) {
                $attr.= ' data-placeholder="'.$first.'" data-allow-clear="true"';
            }

            if (isset($remote)) {
                $attr.= ' data-remote="'.$remote.'"';
            }

            $first = '<option></option>';
        } elseif ($first) {
            $first = '<option>'.$first.'</option>';
        }

        $content = '<select name="'.$name.'" class="am-form-field '.$class.'" '.$change.$multi.$attr.$require.'>';
        $content.= $first;
        $content.= '<?php ';

        // 支持用函数传数组
        if (strpos($value, ":") === 0) {
            $var = '$_' . uniqid();
            $value = $this->autoBuildVar($value);
            $content.= $var . '=' . $value . '; ';
            $value = $var;
        } elseif (strpos($value, "$") === 0) {
            $value = $this->autoBuildVar($value);
            $content.= $value . ' = isset('. $value .') ? '. $value. ' : ""; ';
        } else {
            $var = '$_' . uniqid();
            $content.= $var . '= "' . $value . '"; ';
            $value = $var;
        }

        // 支持用函数传数组
        if (strpos($options, ":") === 0) {
            $var = '$_' . uniqid();
            $options = $this->autoBuildVar($options);
            $content.= $var . '=' . $options . '; ';
            $options = $var;
        } elseif (strpos($options, "$") === 0) {
            $options = $this->autoBuildVar($options);
        }

        $content.= 'if(is_array(' . $options . ') || ' . $options . ' instanceof \think\Collection): ';
        $content.= 'foreach ('. $options .' as $k => $v): ?>';

        if ($fields) {
            $content.= '<?php $_selected = ($v['.$fields[0].'] == '.$value.' && '.$value.' != "") ? " selected" : "";?>';
            $content.= '<option value="<?php echo $v['.$fields[0].'];?>"<?php echo $_selected;?>><?php echo $v['.$fields[1].'];?></option>';
        } else {
            $content.= '<?php $_selected = ($k == '.$value.' && '.$value.' != "") ? " selected" : "";?>';
            $content.= '<option value="<?php echo $k;?>"<?php echo $_selected;?>><?php echo $v;?></option>';
        }

        $content.= '<?php endforeach; endif; ?></select>';

        return $this->tagLabel($tag, $content);
    }

    public function tagText($tag)
    {
        // name,value,class,attr

        return $this->tagLabel($tag, $content);
    }

    public function tagCheckbox($tag)
    {
        // name,value,class,attr

        return $this->tagLabel($tag, $content);
    }

    public function tagRadio($tag)
    {
        // name,value,class,attr

        return $this->tagLabel($tag, $content);
    }
}