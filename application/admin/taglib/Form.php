<?php
/**
 * 表单模版标签库
 * Created by PhpStorm.
 * @package app\admin\taglib
 * @version 16/8/1 下午5:09
 * @author  cbwfree
 */
namespace app\admin\taglib;

use think\Url;
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
        'form'      => ['attr' => 'id,submit,class,method,valid,goto,index,pk', 'expression' => true, 'level' => 0],
        'fieldset'  => ['attr' => 'class,title', 'expression' => true, 'level' => 0],
        'label'     => ['attr' => 'label,width,help'],
        'input'     => ['attr' => 'label,width,help,valid,default,id,name,type,value,class,tips,help,attr', 'close' => 0],
        'select'    => ['attr' => 'label,width,help,valid,default,id,name,options,fields,value,class,first,remote,multi,attr', 'close' => 0],
        'text'      => ['attr' => 'label,width,help,valid,default,id,name,value,class,height,tips,attr', 'close' => 0],
        'checkbox'  => ['attr' => 'label,width,help,valid,default,name,options,value,class,attr', 'close' => 0],
        'radio'     => ['attr' => 'label,width,help,valid,default,name,options,value,class,attr', 'close' => 0],
        'hidden'    => ['attr' => 'default,id,name,value', 'close' => 0],
        'token'     => ['attr' => 'name,type', 'expression' => true, 'close' => 0],
    ];

    /**
     * 创建表单
     * @param $tag
     *      id          Form ID属性
     *      submit      表单提交URL, 支持变量和函数
     *      class       Class属性
     *      method      表单提交方式, 可选 post 或 get
     *      ajax        是否Ajax方式提交表单
     *      valid       是否开启表单验证
     *      goto        跳转URL
     *      index       编辑数据索引
     *      pk          编辑数据索引键名, 默认id
     * @param $content
     * @return string
     */
    public function tagForm($tag, $content)
    {
        $id     = isset($tag['id']) ? ' id="'.$tag['id'].'"' : '';
        $submit = isset($tag['submit']) ? $tag['submit'] : '';
        $class  = isset($tag['class']) ? $tag['class'] : '';
        $method = isset($tag['method']) ? $tag['method'] : 'post';
        $ajax   = isset($tag['ajax']) ? (bool) $tag['ajax'] : true;
        $valid  = isset($tag['valid']) ? ' data-validator' : '';
        $index  = isset($tag['index']) ? $tag['index'] : '';
        $pk     = isset($tag['pk']) ? $tag['pk'] : 'id';
        $goto   = isset($tag['goto']) ? $tag['goto'] : '';

        if ($ajax) {
            $ajax = ' data-ajax-submit';
        }

        // 支持用函数传数组
        $flag  = substr($submit, 0, 1);
        if (":" == $flag || "$" == $flag) {
            $submit = '<?php echo '.$this->autoBuildVar($submit).'; ?>';
        }

        if (! empty($index)) {
            $flag  = substr($index, 0, 1);
            if (":" == $flag) {
                $index = '<?php echo '.$this->autoBuildVar($index).'; ?>';
            } else {
                if ("$" != $flag) {
                    $index = '$' . $index;
                }
                $index = $this->autoBuildVar($index);
                $index = '<?php echo isset('.$index.') ? '.$index.' : \'\'; ?>';
            }
        }

        if (! empty($goto)) {
            $flag  = substr($goto, 0, 1);
            if (":" == $flag || "$" == $flag) {
                $goto = '<?php echo '.$this->autoBuildVar($goto).'; ?>';
            } else {
                $goto = Url::build($goto);
            }
        }

        $parse = '<form'.$id.' action="'.$submit.'" method="'.$method.'" class="am-form am-form-horizontal am-form-xs '.$class.'"'.$ajax.$valid.'>';
        $parse.= $content;
        $parse.= '<div class="am-form-group">';
        $parse.= '<div class="am-u-sm-8 am-u-md-10 am-u-sm-offset-4 am-u-md-offset-2">';
        $parse.= '<input type="hidden" name="'.$pk.'" value="'.$index.'" />';
        if ($goto) {
            $parse.= '<input type="hidden" name="goto" value="'.$goto.'" />';
        }
        $parse.= '<button type="submit" class="am-btn am-btn-primary"><i class="am-icon-save"></i> 保存数据</button>&nbsp;&nbsp;';
        $parse.= '<button type="reset" class="am-btn am-btn-default"><i class="am-icon-repeat"></i> 重置表单</button>';
        $parse.= '</div></div></form>';

        return $parse;
    }

    /**
     * 表单 Fieldset
     * @param $tag
     *      class       Fieldset Class 样式
     *      title       Fieldset 标题内容
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
     *      width       输入框容器宽度, 范围 1 ~ 10
     *      help        帮助信息
     * @param $content
     * @return string
     */
    public function tagLabel($tag, $content)
    {
        // label,width,help
        $width  = isset($tag['width']) ? intval($tag['width']) : 4;
        $help   = isset($tag['help']) ? $tag['help'] : '';

        if ($width <= 0) {
            $width = 4;
        } elseif ($width > 10) {
            $width = 4;
        }

        if (empty($help) && $width < 10) {
            $end = ' am-u-end';
        } else {
            $end = '';
        }

        $help_width = 10 - $width;

        $parse = '<div class="am-form-group">';
        $parse.= '<label class="am-u-sm-4 am-u-md-2 am-form-label">'.$tag['label'].'</label>';
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
     *      name        Input name属性
     *      id          Input id属性
     *      type        Input type属性
     *      value       Input value属性
     *      class       Input class属性
     *      tips        Input 占位内容
     *      attr        Input 额外属性或自定义属性
     *      default     Input 默认value属性
     *      valid       Input 表单验证规则
     * @return string
     */
    public function tagInput($tag)
    {
        // name,type,value,class,tips,help,attr
        $name   = $tag['name'];
        $id     = isset($tag['id']) ? ' id="'.$tag['id'].'"' : '';
        $type   = isset($tag['type']) ? $tag['type'] : 'text';
        $value  = isset($tag['value']) ? $tag['value'] : '';
        $class  = isset($tag['class']) ? $tag['class'] : '';
        $tips   = isset($tag['tips']) ? $tag['tips'] : '';
        $attr   = isset($tag['attr']) ? ' '.$tag['attr'] : '';
        $default= isset($tag['default']) ? $tag['default'] : '';

        // 表单验证
        $valid  = $this->formValid(isset($tag['valid']) ? $tag['valid'] : '');

        // 支持用函数传数组
        $flag  = substr($value, 0, 1);
        if (":" == $flag) {
            $value = '<?php echo '.$this->autoBuildVar($value).'; ?>';
        } elseif ("$" == $flag) {
            $value = $this->autoBuildVar($value);
            $value = '<?php echo isset('.$value.') ? '.$value.': \''.$default.'\';?>';
        }

        if ($type == "password2") {
            $type = "password";
            $password = true;
        } else {
            $password = false;
        }

        $input = '<input type="'.$type.'"'.$id.' name="'.$name.'" value="'.$value.'" class="am-form-field '.$class.$valid['class'].'" placeholder="'.$tips.'"'.$attr.$valid['rule'].' />';
        if ($password) {
            $content = '<div class="am-form-password">';
            $content.= $input;
            $content.= '<i class="am-password-icon am-icon-eye"></i></div>';
        } else {
            $content = $input;
        }

        return $this->tagLabel($tag, $content);
    }

    /**
     * 创建隐藏域
     * @param $tag
     * @return string
     */
    public function tagHidden($tag)
    {
        $name   = $tag['name'];
        $id     = isset($tag['id']) ? ' id="'.$tag['id'].'"' : '';
        $value  = isset($tag['value']) ? $tag['value'] : '';
        $default= isset($tag['default']) ? $tag['default'] : '';

        // 支持用函数传数组
        $flag  = substr($value, 0, 1);
        if (":" == $flag) {
            $value = '<?php echo '.$this->autoBuildVar($value).'; ?>';
        } elseif ("$" == $flag) {
            $value = $this->autoBuildVar($value);
            $value = '<?php echo isset('.$value.') ? '.$value.': \''.$default.'\';?>';
        }

        $parse = '<input type="hidden"'.$id.' name="'.$name.'" value="'.$value.'" />';

        return $parse;
    }

    /**
     * 创建文本输入框
     * @param $tag
     *      name        Textarea name属性
     *      id          Textarea id属性
     *      value       Textarea 内容
     *      class       Textarea class属性
     *      tips        Textarea 占位内容
     *      attr        Textarea 额外属性或自定义属性
     *      height      Textarea 高度
     *      default     Textarea 默认内容
     *      valid       Textarea 表单验证规则
     * @return string
     */
    public function tagText($tag)
    {
        // name,type,value,class,tips,help,attr
        $name   = $tag['name'];
        $id     = isset($tag['id']) ? ' id="'.$tag['id'].'"' : '';
        $value  = isset($tag['value']) ? $tag['value'] : '';
        $class  = isset($tag['class']) ? $tag['class'] : '';
        $tips   = isset($tag['tips']) ? $tag['tips'] : '';
        $attr   = isset($tag['attr']) ? ' '.$tag['attr'] : '';
        $height = isset($tag['height']) ? 'height:'.$tag['height'].'px;' : '';
        $default= isset($tag['default']) ? $tag['default'] : '';

        // 表单验证
        $valid  = $this->formValid(isset($tag['valid']) ? $tag['valid'] : '');

        // 支持用函数传数组
        $flag  = substr($value, 0, 1);
        if (":" == $flag) {
            $value = '<?php echo '.$this->autoBuildVar($value).'; ?>';
        } elseif ("$" == $flag) {
            $value = $this->autoBuildVar($value);
            $value = '<?php echo isset('.$value.') ? '.$value.': \''.$default.'\';?>';
        }

        $content = '<textarea name="'.$name.'"'.$id.' class="am-form-field'.$class.$valid['class'].'" style="'.$height.'" placeholder="'.$tips.'"'.$attr.$valid['rule'].'>'.$value.'</textarea>';

        return $this->tagLabel($tag, $content);
    }

    /**
     * 创建选择列表
     * @param $tag
     *      name        Select name属性
     *      id          Select id属性
     *      value       Select 当前选中值, 多选时
     *      class       Select class属性
     *      first       Select 占位内容(空选项)
     *      options     Select 选项列表
     *      remote      Select2 Ajax请求URL
     *      attr        Select 额外属性或自定义属性
     *      multi       Select 是否多选列表
     *      default     Select 默认选择
     *      valid       Select 表单验证规则
     * @return string
     */
    public function tagSelect($tag)
    {
        // name,options,value,class,attr,first
        $name   = $tag['name'];
        $id     = isset($tag['id']) ? ' id="'.$tag['id'].'"' : '';
        $value  = isset($tag['value']) ? $tag['value'] : '';
        $class  = isset($tag['class']) ? ' '.$tag['class'] : '';
        $first  = isset($tag['first']) ? $tag['first'] : '';
        $options= isset($tag['options']) ? $tag['options'] : [];
        $remote = isset($tag['remote']) ? $tag['remote'] : '';
        $attr   = isset($tag['attr']) ? ' '.$tag['attr'] : '';
        $multi  = isset($tag['multi']) ? ' multiple' : '';
        $fields = isset($tag['fields']) ? explode(',', $tag['fields']) : '';
        $default= isset($tag['default']) ? $tag['default'] : '';

        // 表单验证
        $valid  = $this->formValid(isset($tag['valid']) ? $tag['valid'] : '');

        // 处理 Select2 扩展
        if (strpos($attr, "data-select") !== false) {
            if ($first) {
                $attr.= ' data-placeholder="'.$first.'" data-allow-clear="true"';
                $first = '<option></option>';
            }

            if (isset($remote)) {
                // AjaxURL参数解析
                $flag  = substr($remote, 0, 1);
                if (":" == $flag || "$" == $flag) {
                    $remote = '<?php echo '.$this->autoBuildVar($remote).'; ?>';
                }
                $attr.= ' data-remote="'.$remote.'"';
            }
        } elseif ($first) {
            $first = '<option>'.$first.'</option>';
        }

        $content = '<select'.$id.' name="'.$name.'" class="am-form-field'.$class.$valid['class'].'"'.$multi.$attr.$valid['rule'].'>';
        $content.= $first;
        $content.= '<?php ';

        // 选中项参数解析, 支持用函数传数组
        $flag  = substr($value, 0, 1);
        if (":" == $flag) {
            $var = '$_' . uniqid();
            $value = $this->autoBuildVar($value);
            $content.= $var . '=' . $value . '; ';
            $value = $var;
        } elseif ("$" == $flag) {
            $value = $this->autoBuildVar($value);
            $content .= $value . ' = isset(' . $value . ') ? ' . $value . ' : "' . $default . '"; ';
        } elseif ("[" == $flag) {
            $value = '';
        } else{
            $value = '\''.$value.'\'';
        }

        // 选项列表参数解析, 支持用函数传数组
        $flag  = substr($options, 0, 1);
        if (":" == $flag) {
            $var = '$_' . uniqid();
            $options = $this->autoBuildVar($options);
            $content.= $var . '=' . $options . '; ';
            $options = $var;
        } elseif ("$" == $flag) {
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

    /**
     * 创建多选框列表
     * @param $tag
     *      name        Checkbox name属性
     *      value       Checkbox 选中项值
     *      class       Checkbox class属性
     *      options     Checkbox 选项数组, 支持变量或函数
     *      default     Checkbox 默认选中项
     *      valid       Checkbox 表单验证规则
     * @return string
     */
    public function tagCheckbox($tag)
    {
        // name,options,value,class
        $name   = $tag['name'];
        $value  = isset($tag['value']) ? $tag['value'] : '';
        $class  = isset($tag['class']) ? $tag['class'] : '';
        $options= isset($tag['options']) ? $tag['options'] : [];
        $default= isset($tag['default']) ? $tag['default'] : '';

        // 表单验证
        $valid  = $this->formValid(isset($tag['valid']) ? $tag['valid'] : '');

        $content = '<?php ';

        // 支持用函数传数组
        $flag  = substr($value, 0, 1);
        if (":" == $flag) {
            $var = '$_' . uniqid();
            $value = $this->autoBuildVar($value);
            $content.= $var . '=' . $value . '; ';
            $value = $var;
        } elseif ("$" == $flag) {
            $value = $this->autoBuildVar($value);
            $content .= $value . ' = isset(' . $value . ') ? ' . $value . ' : \''.$default.'\'; ';
        } elseif ("[" == $flag) {
            $var = '$_' . uniqid();
            $content.= $var . '= '. $value .'; ';
            $value = $var;
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
        $content.= '$_index = 0;';
        $content.= 'foreach ('. $options .' as $k => $v): ';
        $content.= 'if ($_index === 0): $_valid_rule = "'.$valid['rule'].'"; $_valid_class = "'.$valid['class'].'"; ';
        $content.= 'else: $_valid_rule = ""; $_valid_class = ""; endif;';
        $content.= '$_checked = ($k == '.$value.' || (is_array('.$value.') && in_array($k, '.$value.'))) ? " checked" : ""; ?>';
        $content.= '<label class="am-checkbox-inline'.$class.'">';
        $content.= '<input type="checkbox" name="'.$name.'" class="<?php echo $_valid_class; ?>" value="<?php echo $k; ?>"<?php echo $_valid_rule; ?><?php echo $_checked;?> /> <?php echo $v; ?>';
        $content.= '</label>';
        $content.= '<?php $_index++; endforeach; endif; ?>';

        return $this->tagLabel($tag, $content);
    }

    /**
     * 创建单选框列表
     * @param $tag
     *      name        Radio name属性
     *      value       Radio 选中项值
     *      class       Radio class属性
     *      options     Radio 选项数组, 支持变量或函数
     *      default     Radio 默认选中项
     *      valid       Radio 表单验证规则
     * @return string
     */
    public function tagRadio($tag)
    {
        // name,options,value,class
        $name   = $tag['name'];
        $value  = isset($tag['value']) ? $tag['value'] : '';
        $class  = isset($tag['class']) ? $tag['class'] : '';
        $options= isset($tag['options']) ? $tag['options'] : [];
        $default= isset($tag['default']) ? $tag['default'] : '';

        // 表单验证
        $valid  = $this->formValid(isset($tag['valid']) ? $tag['valid'] : '');

        $content = '<?php ';

        // 支持用函数传数组
        $flag  = substr($value, 0, 1);
        if (":" == $flag) {
            $var = '$_' . uniqid();
            $value = $this->autoBuildVar($value);
            $content.= $var . '=' . $value . '; ';
            $value = $var;
        } elseif ("$" == $flag) {
            $value = $this->autoBuildVar($value);
            $content.= $value . ' = isset('. $value .') ? '. $value. ' : \''.$default.'\'; ';
        } else {
            $var = '$_' . uniqid();
            $content.= $var . '= "' . $value . '"; ';
            $value = $var;
        }

        // 支持用函数传数组
        $flag  = substr($options, 0, 1);
        if (":" == $flag) {
            $var = '$_' . uniqid();
            $options = $this->autoBuildVar($options);
            $content.= $var . '=' . $options . '; ';
            $options = $var;
        } elseif ("$" == $flag) {
            $options = $this->autoBuildVar($options);
        }

        $content.= 'if(is_array(' . $options . ') || ' . $options . ' instanceof \think\Collection): ';
        $content.= '$_index = 0;';
        $content.= 'foreach ('. $options .' as $k => $v): ';
        $content.= 'if ($_index === 0): $_valid_rule = "'.$valid['rule'].'"; $_valid_class = "'.$valid['class'].'"; ';
        $content.= 'else: $_valid_rule = ""; $_valid_class = ""; endif;';
        $content.= '$_checked = $k == '.$value.' ? " checked" : ""; ?>';
        $content.= '<label class="am-radio-inline'.$class.'">';
        $content.= '<input type="radio" name="'.$name.'" class="<?php echo $_valid_class; ?>"  value="<?php echo $k; ?>"<?php echo $_valid_rule; ?><?php echo $_checked;?> /> <?php echo $v; ?>';
        $content.= '</label>';
        $content.= '<?php $_index++; endforeach; endif; ?>';

        return $this->tagLabel($tag, $content);
    }

    /**
     * 创建表单令牌
     * @param $tag
     * @return string
     */
    protected function tagToken($tag)
    {
        $name = isset($tag['name']) ? $tag['name'] : '__token__';
        $type = isset($tag['type']) ? $tag['type'] : 'md5';

        $token = Request::instance()->token($name, $type);
        $parse = '<input type="hidden" name="'.$name.'" value="'.$token.'" />';

        return$parse;
    }

    /**
     * 表单验证规则解析
     * @param string $rules 规则字符串, 格式: 规则名称:规则属性|规则名称:规则属性, 例如: required|length:3,4
     *      required               必填
     *      equal:选择器            匹配指定字段的值
     *      regex:正则              使用正则表达式
     *      min:数值               最小值
     *      max:数值               最大值
     *      length:数值,数值        长度范围
     *      checked:数值,数值       选择项范围
     * @return string
     */
    protected function formValid($rules)
    {
        $valid = [
            'rule'      => [],
            'class'     => [],
        ];

        if (! empty($rules)) {
            $rules = explode("|", $rules);

            foreach ($rules as $rule) {
                if ($rule == "required") {
                    $valid['rule'][] = "required";
                } elseif (strpos($rule, ":")) {
                    $rule = explode(":", $rule, 2);

                    switch($rule[0]) {
                        // 匹配其它字段
                        case 'equal':
                            $valid['rule'][] = 'data-equal-to="'.$rule[1].'"';
                            break;
                        // 最小值
                        case 'min':
                            if (is_numeric($rule[1])) {
                                $valid['rule'][] = 'min="'.$rule[1].'"';
                            }
                            break;
                        // 最大值
                        case 'max':
                            if (is_numeric($rule[1])) {
                                $valid['rule'][] = 'max="'.$rule[1].'"';
                            }
                            break;
                        // 字符长度范围
                        case 'length':
                            list($min, $max) = explode(",", $rule[1]);
                            if (is_numeric($min)) {
                                $valid['rule'][] = 'minlength="'.$min.'"';
                            }
                            if (is_numeric($max)) {
                                $valid['rule'][] = 'maxlength="'.$max.'"';
                            }
                            break;
                        // 选择项数量范围
                        case 'checked':
                            list($min, $max) = explode(",", $rule[1]);
                            if (is_numeric($min)) {
                                $valid['rule'][] = 'minchecked="'.$min.'"';
                            }
                            if (is_numeric($max)) {
                                $valid['rule'][] = 'maxchecked="'.$max.'"';
                            }
                            break;
                    }
                } else {
                    // 使用 AMUI 定义的正则库验证
                    $valid['class'][] = "js-pattern-" . $rule;
                }
            }
        }


        $valid['rule'] = $valid['rule'] ? " " . implode(" ", $valid['rule']) : "";
        $valid['class'] = $valid['class'] ? " " . implode(" ", $valid['class']) : "";

        return $valid;
    }
}