<?php
/**
 * 搜索框组件
 * Created by PhpStorm.
 * @package app\admin\extend
 * @version 16/8/1 下午5:09
 * @author  cbwfree
 */
namespace app\admin\extend;

use traits\think\Instance;
use think\Request;

/**
 * 搜索框组件
 * Class Search
 * @package app\admin\extend
 */
class Search
{
    /**
     * 使用单例
     */
    use Instance;

    const TYPE_HIDDEN   = 'Hidden';      // 隐藏域
    const TYPE_SEARCH   = 'Search';      // 单项搜索框
    const TYPE_SELECT   = 'Select';      // 下拉列表
    const TYPE_SELECT2  = 'Select2';     // Select2下拉列表
    const TYPE_DATETIME = 'Datetime';    // 日期时间选择框

    /**
     * @var null|Request    Request对象
     */
    protected $request = null;

    /**
     * @var array           GET参数数组
     */
    protected $values = [];

    /**
     * @var array       搜索框表单组件
     */
    protected $controls = [];

    /**
     * @var array        模糊查询字段选择列表
     */
    protected $fields = [];

    /**
     * @var array       参数
     */
    protected $options = [
        'field'     => 'field',             // 组合查询选择框字段
        'query'     => 'query',             // 组合查询输入框字段
    ];

    /**
     * Search constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->request  = Request::instance();
        $this->values   = $this->request->get('', null, ['urldecode']);
        $this->options  = array_merge($this->options, $options);
    }

    /**
     * 设置组合查询字段
     * @param array $fields     字段数组
     */
    public function setFields(array $fields = [])
    {
        $this->fields = $fields;
    }

    /**
     * 增加搜索表单控件
     * @param string $name      控件name
     * @param string $type      控件类型
     * @param string $label     控件标识名称
     * @param array  $params    控件参数
     */
    public function control($name, $type, $label = '', array $params = [])
    {
        $control = [
            "type"      => $type,
            "name"      => $name,
            "label"     => $label,
            "params"    => $params
        ];
        $this->controls[] = $control;
    }

    /**
     * 创建搜索表单HTML
     * @param array $params 默认参数
     * @return string
     */
    public function html(array $params = [])
    {
        $form = '';

        if (! empty($this->controls) or ! empty($this->fields)) {
            $form.= '<form id="page-search" class="am-form am-form-inline am-form-xs" onsubmit="return page_search();">';
            $form.= '<div class="search-icon am-form-group"><span class="am-badge"><i class="am-icon-search"></i></span></div>';

            // 创建模糊查询框
            if (! empty($this->fields)) {
                $form.= $this->buildFormFieldsQuery($this->value($this->options['field']), $this->value($this->options['query'])).'&nbsp;';
            }

            // 创建控件
            if (! empty($this->controls)) {
                foreach ($this->controls as $control) {
                    $value = $this->getSearchValue($control['name'], $control['params']);
                    $type  = ucfirst($control['type']);

                    if ($type == self::TYPE_HIDDEN) {
                        $form.= '<input type="hidden" name="' . $control['name'] . '" value="' . $value . '">';
                        continue;
                    }

                    if ($type == self::TYPE_SELECT2) {
                        $type = self::TYPE_SELECT;
                        $control['params']['select2'] = true;
                    }

                    if (method_exists($this, 'buildForm' . $type)) {
                        $form.= call_user_func_array([$this, 'buildForm' . $type], [
                            $control['name'],
                            $value,
                            $control['label'],
                            $control['params'],
                        ]).'&nbsp;';
                    }
                }
            }

            // 搜索按钮
            $form.= '<div class="am-btn-group am-fr">';
            $form.= '<button type="submit" class="am-btn am-btn-success"><i class="am-icon-search"></i> 搜索</button>';
            $form.= '<a href="'.url($this->request->action(), $params).'" class="am-btn am-btn-warning"><i class="am-icon-reply"></i> 撤销</a>';
            $form.= '</div>';
            $form.= '</form>';
        }

        return $form;
    }

    /**
     * 构建查询条件
     * @param array $params
     * @return array
     */
    public function query($params = [])
    {
        $condition = [];

        return $condition;
    }

    /**
     * 获取请求参数值
     * @param string $name      字段
     * @param string $default   默认值
     * @return string
     */
    protected function value($name, $default = '')
    {
        return isset($this->values[$name]) ? $this->values[$name] : $default;
    }

    /**
     * 获取搜索值
     * @param string $name      控件字段
     * @param array $params     控件属性
     * @return array|string
     */
    protected function getSearchValue($name, array $params = [])
    {
        if (isset($params['range']) && $params['range']) {
            $value = [$this->value($name.'_start'), $this->value($name.'_stop')];
        }else{
            $value = $this->value($name);
        }
        return $value;
    }

    /**
     * 构建组合查询框控件
     * @param string $field     模糊查询字段
     * @param string $query     模糊查询值
     * @return string
     */
    protected function buildFormFieldsQuery($field, $query)
    {
        $html = '<div class="am-form-group">';
        $html.= '<select name="'.$this->options['field'].'" class="am-form-field search-query">';

        foreach ($this->fields as $val => $label) {
            $selected = (! empty($field) && $field == $val) ? " selected" : '';
            $html.= '<option value="'.$val.'"'.$selected.'>'.$label.'</option>';
        }

        $html.= '</select></div><div class="am-form-group">';
        $html.= '<input type="search" class="am-form-field search-query" name="'.$this->options['query'].'" value="'.$query.'" placeholder="模糊查询">';
        $html.= '</div>';

        return $html;
    }

    /**
     * 构建搜索框控件
     * @param string $name          控件name
     * @param string|array $value   控件value
     * @param string $label         控件label
     * @param array $params         控件参数
     *      width:      string          控件宽度
     *      icon:       string          控件图标 (默认无图标)
     * @return string
     */
    protected function buildFormSearch($name, $value, $label, array $params = [])
    {
        // 参数检查
        $icon   = isset($params['icon']) ? $params['icon'] : '';
        $width  = isset($params['width']) ? "width:{$params['width']};" : '';

        // 创建HTML
        $html = '<div class="am-form-group'.($icon ? ' am-form-icon' : '').'">';
        if ($icon) {
            $html.= '<i class="'.$icon.'"></i>';
        }
        $html.= '<input type="search" class="am-form-field" name="'.$name.'" value="'.$value.'" placeholder="'.$label.'" style="'.$width.'">';
        $html.= '</div>';
        return $html;
    }

    /**
     * 构建下拉框控件
     * @param string $name          控件name
     * @param string|array $value   控件value
     * @param string $label         控件label
     * @param array $params         控件参数
     *      options:    array           选项数组
     *      fields:     array|string    选项数组的字段信息 [value, label]
     *      change:     string          onChange事件名称
     *      space:      string          Tree选择列表缩进字符
     *      group:      bool            分组模式 (Tree选项父级不可选)
     *      multiple:   bool            是否启用多选
     *      width:      string          select控件宽度
     *      select2:    bool            使用Select2扩展原始Select控件
     *      icon:       string          控件图标 (仅普通Select有效)
     * @return string
     */
    protected function buildFormSelect($name, $value, $label, array $params = [])
    {
        // 参数检查
        $options    = isset($params['options']) ? $params['options'] : [];
        $fields     = isset($params['fields']) ? (is_string($params['fields']) ? explode(",", $params['fields']) : $params['fields']) : false;
        $change     = isset($params['change']) ? " onchange='{$params['change']}'" : '';
        $space      = isset($params['space']) ? $params['space'] : '&emsp;&emsp;';
        $group      = isset($params['group']) ? $params['group'] : false;
        $multiple   = isset($params['multiple']) ? ' multiple' : '';
        $width      = isset($params['width']) ? "width:{$params['width']};" : '';
        $select2    = isset($params['select2']) ? ' data-select' : '';
        $icon       = isset($params['icon']) && ! $select2 ? $params['icon'] : '';

        // 创建HTML
        $html = '<div class="am-form-group'.($icon ? ' am-form-icon' : '').'">';
        if ($icon) {
            $html.= '<i class="'.$icon.'"></i>';
        }
        $html.= '<select name="'.$name.'" class="am-form-field" style="'.$width.'"'.$change.$multiple.$select2.'>';

        if ( $label ) {
            $html.= '<option value="">- '.$label.' -</option>';
        }

        // 匿名函数返回选择列表
        if ($options instanceof \Closure) {
            $options = call_user_func_array($options, [$value, $this->values]);
        }

        if ($fields) {
            // 使用指定字段模式创建选项列表 (支持Tree和分组模式)
            $get_tree_option = function($options, $level = 0, $space = '&emsp;&emsp;') use ($fields, $group, $value, &$get_tree_option){
                $opt_str = "";
                foreach($options as $val){
                    $child_opt = '';
                    if (isset($val['_child']) && ! empty($val['_child']) && is_array($val['_child'])) {
                        $child_opt = $get_tree_option($val['_child'], $level + 1, $space);
                    }

                    if ($level === 0 && $group) {
                        $opt_str.= '<optgroup label="'.$val[$fields[1]].'">'.$child_opt.'</optgroup>';
                    } else {
                        $opt_str.= '<option value="'.$val[$fields[0]].'"'.check_selected($val[$fields[0]], $value).'>'.tree_indent($level, $space).$val[$fields[1]].'</option>';
                        $opt_str.= $child_opt;
                    }
                }
                return $opt_str;
            };
            $html.= $get_tree_option($options, 0, $space);
        } else {
            foreach($options as $key => $val){
                $html.= '<option value="'.$key.'"'.check_selected($key, $value).'>'.$val.'</option>';
            }
        }

        $html.= '</select></div>';
        return $html;
    }

    /**
     * 构建日期时间选择控件
     * @param string $name          控件name
     * @param string|array $value   控件value
     * @param string $label         控件label
     * @param array $params         控件参数
     *      format:     string          日期类型(预定义格式: date, time, datetime, 可自定义), 默认 datetime
     *      width:      string          控件宽度(自定义 format 有效)
     *      icon:       string          控件图标(自定义 format 有效)
     *      range:      bool            是否范围选择, 默认 false
     *      scope:      array|string    时间的可选范围 (range = false 有效)
     *      start:      array|string    开始时间的可选范围 (range = true 有效)
     *      stop:       array|string    结束时间的可选范围 (range = true 有效)
     * @return string
     */
    protected function buildFormDatetime($name, $value, $label, array $params = [])
    {
        // 日期格式
        $format = isset($params['format']) ? $params['format'] : 'datetime';
        switch ($format) {
            case 'datetime':
                $format = 'YYYY-MM-DD HH:mm:ss';
                $width  = "width:174px;";
                $icon   = "am-icon-calendar";
                break;
            case 'date':
                $format = "YYYY-MM-DD";
                $width  = "width:116px;";
                $icon   = "am-icon-calendar-o";
                break;
            case 'time':
                $format = "HH:mm:ss";
                $width  = "width:94px;";
                $icon   = "am-icon-clock-o";
                break;
            default:
                $width  = isset($params['width']) ? "width:{$params['width']};" : '';
                $icon   = isset($params['icon']) ? $params['icon'] : 'am-icon-calendar';
        }

        // 生成HTML
        $get_datetime_search = function($name, $value, $label, $min = '', $max = '') use ($format, $icon, $width){
            $datetime = '<div class="am-form-group'.($icon ? ' am-form-icon' : '').'">';
            if ($icon) {
                $datetime.= '<i class="'.$icon.'"></i>';
            }

            $min = empty($min) ? ' data-min="'.$min.'"' : '';
            $max = empty($max) ? ' data-max="'.$max.'"' : '';

            $datetime.= '<input type="search" class="am-form-field" name="'.$name.'" value="'.$value.'" style="'.$width.'" placeholder="'.$label.'" data-datetime="'.$format.'"'.$min.$max.'/>';
            $datetime.= '</div>';
            return $datetime;
        };

        // 创建HTML
        if( isset($params['range']) && $params['range'] ){
            $start_min = $start_max = '';
            $stop_min = $stop_max = '';
            if (isset($params['start'])) {
                if (is_array($params['start'])){
                    list($start_min, $start_max) = $params['start'];
                }else{
                    $start_min = $params['start'];
                    $start_max = '';
                }
            }
            if (isset($params['stop'])) {
                if (is_array($params['stop'])){
                    list($stop_min, $stop_max) = $params['stop'];
                }else{
                    $stop_min = $params['stop'];
                    $stop_max = '';
                }
            }

            $html = $get_datetime_search($name."_start", $value[0], $label.'开始', $start_min, $start_max);
            $html.= '<div class="search-range am-form-group"><span class="am-badge"><i class="am-icon-minus"></i></span></div>';
            $html.= $get_datetime_search($name."_stop", $value[1], $label.'结束', $stop_min, $stop_max);
        }else{
            $scope = isset($params['scope']) ? $params['scope'] : '';
            if (is_array($scope)) {
                list($min, $max) = $scope;
            } else {
                $min = $scope;
                $max = "";
            }
            $html = $get_datetime_search($name, $value, $label, $min, $max);
        }

        return $html;
    }
}