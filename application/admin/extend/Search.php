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
    const TYPE_SEARCH   = 'Search';      // 搜索框
    const TYPE_SELECT   = 'Select';      // 下拉列表
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
     * @var string      控件默认宽度
     */
    protected $width = '140px';

    /**
     * Search constructor.
     */
    public function __construct()
    {
        $this->request  = Request::instance();
        $this->values   = $this->request->get();
    }

    /**
     * 增加搜索表单控件
     * @param string $name      控件name
     * @param string $type      控件类型
     * @param string $label     控件标识名称
     * @param string $width     控件宽度
     * @param array  $params    控件参数
     */
    public function control($name, $type, $label = '', $width = '', array $params = [])
    {
        $control = [
            "type"      => $type,
            "name"      => $name,
            "label"     => $label,
            "width"     => $width,
            "params"    => $params
        ];
        $this->controls[] = $control;
    }

    /**
     * 创建搜索表单
     * @param array $params 默认参数
     * @return string
     */
    public function form(array $params = [])
    {
        $form = [];

        if (! empty($this->controls)) {
            $form[] = '<form id="page-search" class="am-form am-form-inline am-form-xs" onsubmit="return page_search();">';

            // 创建控件
            foreach($this->controls as $control) {
                $func = 'buildForm' . ucfirst($control['type']);
                if (method_exists($this, $func)) {
                    $html[] = call_user_func_array([$this, $func], [
                        $control['name'],
                        $this->getSearchValue($control['name'], $control['params']),
                        $control['label'],
                        $control['width'],
                        $control['params'],
                    ]);
                }
            }

            // 搜索按钮
            $form[] = '<div class="am-btn-group am-fr">';
            $form[] = '<button type="submit" class="am-btn am-btn-success"><i class="am-icon-search"></i> 搜索</button>';
            $form[] = '<a href="'.url($this->request->action(), $params).'" class="am-btn am-btn-warning"><i class="am-icon-reply"></i> 撤销</a>';
            $form[] = '</div>';
            $form[] = '</form>';
        }

        return implode("", $form);
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
     * @param string $name      参数名称
     * @param string $default   默认值
     * @return string
     */
    protected function value($name, $default = '')
    {
        return isset($this->values[$name]) ? $this->values[$name] : $default;
    }

    /**
     * 获取搜索值
     * @param string $name      参数名称
     * @param array $params     控件属性
     * @return array|string
     */
    protected function getSearchValue($name, $params = [])
    {
        if (isset($params['range']) && $params['range']) {
            $value = [$this->value($name.'_start'), $this->value($name.'_stop')];
        }else{
            $value = $this->value($name);
        }
        return $value;
    }

    /**
     * 构建隐藏域控件
     * @param string $name          控件name
     * @param string|array $value   控件value
     * @param string $label         控件label
     * @param string $width         控件宽度
     * @param array $params         控件参数
     * @return string
     */
    protected function buildFormHidden($name, $value, $label, $width, array $params = [])
    {
        $html = [];
        return implode("", $html);
    }

    /**
     * 构建搜索框控件
     * @param string $name          控件name
     * @param string|array $value   控件value
     * @param string $label         控件label
     * @param string $width         控件宽度
     * @param array $params         控件参数
     * @return string
     */
    protected function buildFormSearch($name, $value, $label, $width, array $params = [])
    {
        $html = [];
        return implode("", $html);
    }

    /**
     * 构建下拉框控件
     * @param string $name          控件name
     * @param string|array $value   控件value
     * @param string $label         控件label
     * @param string $width         控件宽度
     * @param array $params         控件参数
     * @return string
     */
    protected function buildFormSelect($name, $value, $label, $width, array $params = [])
    {
        $html = [];
        return implode("", $html);
    }

    /**
     * 构建日期时间选择控件
     * @param string $name          控件name
     * @param string|array $value   控件value
     * @param string $label         控件label
     * @param string $width         控件宽度
     * @param array $params         控件参数
     * @return string
     */
    protected function buildFormDatetime($name, $value, $label, $width, array $params = [])
    {
        $html = [];
        return implode("", $html);
    }
}