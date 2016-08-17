<?php
/**
 * 分页组件
 * Created by PhpStorm.
 * @package app\admin\extend
 * @version 16/8/1 下午5:09
 * @author  cbwfree
 */
namespace app\admin\extend;

use think\Url;
use think\Request;

/**
 * 分页组件
 * Class Search
 * @package app\admin\extend
 */
class Paging
{
    /**
     * @var array   单例组
     */
    protected static $instances = [];

    /**
     * @var int     数据总数
     */
    protected $count        = 0;

    /**
     * @var int     分页总数
     */
    protected $total        = 0;

    /**
     * @var int     当前页数
     */
    protected $page         = 1;

    /**
     * @var int     默认每页显示数量
     */
    protected $size         = 20;

    /**
     * @var array   页码数组
     */
    protected $range        = [];

    /**
     * 分页参数
     * @var array
     */
    protected $options = [
        'first'     => '<i class="am-icon-step-backward"></i>',         // 第一页
        "last"      => '<i class="am-icon-step-forward"></i>',          // 最后一页
        'prev'      => '<i class="am-icon-backward"></i>',              // 上一页
        'next'      => '<i class="am-icon-forward"></i>',               // 下一页
        'size'      => [10, 20, 30, 50, 100],                           // 可选每页显示数量 (不建议太大)
        'var_page'  => 'page',                                          // 页码变量
        'var_size'  => 'size',                                          // 每页显示数量变量
        'left_num'  => 3,                                               // 左侧页码数量
        'right_num' => 3,                                               // 右侧页码数量
        'show_total'=> true,                                            // 是否显示数据总数信息
        'show_jump' => true,                                            // 是否显示跳转
        'show_size' => true,                                            // 是否可选择每页显示数量
    ];

    /**
     * 获取单利对象
     * @param string $name
     * @param array $options
     * @return Paging
     */
    public static function instance($name = '', $options = [])
    {
        if (! isset(self::$instances[$name])) {
            self::$instances[$name] = new self($options);
        }
        return self::$instances[$name];
    }

    /**
     * Paging constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->options = array_merge($this->options, $config);
    }

    /**
     * 构建分页
     * @param int $count    总记录数
     * @return string
     */
    public function limit($count)
    {
        $request = Request::instance();

        // 参数检查
        $page   = (int) $request->get($this->options['var_page'], $this->page);
        $size   = (int) $request->get($this->options['var_size'], $this->size);
        $page   = $page <= 0 ? $this->page : $page;
        $size   = in_array($size, $this->options['size']) ? $size : $this->size;

        // 分页计算
        $total  = ceil($count / $size) ?: 1;
        $page   = $page > $total ? $total : $page;
        $limit  = ($size * ($page - 1)).",".$size;

        // 分页区间
        $start  = $page - $this->options['left_num'];
        $stop   = $page + $this->options['right_num'];
        $start  = $start > 1 ? $start : 1;
        $stop   = $stop < $total ? $stop : $total;

        // 保存数据
        $this->page     = $page;
        $this->size     = $size;
        $this->total    = $total;
        $this->count    = $count;
        $this->range    = range($start, $stop);

        return $limit;
    }

    /**
     * 创建显示界面
     * @return string
     */
    public function html()
    {
        $request = Request::instance();

        // 处理请求参数
        $params = $request->get();
        $params[$this->options['var_size']] = $this->size;
        $params[$this->options['var_page']] = "[PAGE]";
        $params = array_map(function($v) {
            return urlencode($v);
        }, $params);

        $url = Url::build($request->action(), $params);
        $paging = '<ul id="page-paging" class="am-pagination am-cf">';

        // 创建每页显示数量选择框
        if ($this->options['show_size']) {
            $paging .= '<li class="size"><select name="size" onchange="page_redirect();">';
            foreach ($this->options['size'] as $s) {
                $paging .= '<option value="'.$s.'"'.($this->size == $s ? ' selected' : '').'>'.$s.'</option>';
            }
            $paging .= '</select><li>';
        }

        //首页&上一页
        if( $this->page > 1 ){
            if( $this->options['first'] ){
                $paging .='<li class="page"><a href="'.$this->replace($url, 1).'" title="首页" data-pjax>'.$this->options['first'].'</a></li>';
            }
            if( $this->options['prev'] ){
                $paging .='<li class="page"><a href="'.$this->replace($url, $this->page - 1).'" title="上一页" data-pjax>'.$this->options['prev'].'</a></li>';
            }
        }else{
            if( $this->options['first'] ) {
                $paging .= '<li class="page am-disabled"><span>' . $this->options['first'] . '</span></li>';
            }
            if( $this->options['prev'] ) {
                $paging .= '<li class="page am-disabled"><span>' . $this->options['prev'] . '</span></li>';
            }
        }

        // 分隔符
        if( reset($this->range) > 1 ){
            $paging .='<li class="page"><span>...</span></li>';
        }
        
        // 页码范围
        foreach($this->range as $page){
            if( $page == $this->page ){
                $paging .='<li class="page am-active"><span title="第'.$page.'页">'.$page.'</span></li>';
            }else{
                $paging .='<li class="page"><a href="'.$this->replace($url, $page).'" title="第'.$page.'页" data-pjax>'.$page.'</a></li>';
            }
        }

        // 分隔符
        if( end($this->range) < $this->total ){
            $paging .='<li class="page"><span>...</span></li>';
        }

        //末页&下一页
        if( $this->page < $this->total ){
            if( $this->options['next'] ) {
                $paging .= '<li class="page"><a href="'.$this->replace($url, $this->page + 1).'" title="下一页" data-pjax>' . $this->options['next'] . '</a></li>';
            }
            if( $this->options['last'] ) {
                $paging .= '<li class="page"><a href="'.$this->replace($url, $this->total).'" title="末页" data-pjax>' . $this->options['last'] . '</a></li>';
            }
        }else{
            if( $this->options['next'] ) {
                $paging .= '<li class="page am-disabled"><span>' . $this->options['next'] . '</span></li>';
            }
            if( $this->options['last'] ) {
                $paging .= '<li class="page am-disabled"><span>' . $this->options['last'] . '</span></span></li>';
            }
        }

        //页面跳转
        if( $this->options['show_jump'] ) {
            $paging .= '<li class="jump">';
            $paging .= '<input type="number" class="am-fl" name="page" value="'.$this->page.'" min="1" max="'.$this->total.'" onkeyup="if((event[\'keyCode\']||event[\'which\'])==13){page_redirect();}">';
            $paging .= '<button type="submit" class="am-btn am-btn-xs am-btn-default am-fl" onclick="page_redirect();">Go!</button>';
            $paging .= '</li>';
        }

        // 创建分页详情
        if( $this->options['show_total'] ) {
            $paging .= '<li class="total"><span>显示 '. $this->page . ' / ' . $this->total . ' 页，共 ' . $this->count . ' 条记录</span></li>';
        }

        $paging .= '</ul>';
        return $paging;
    }

    /**
     * 替换URL占位符
     * @param string $url   URL地址
     * @param string $value 替换值
     * @return mixed
     */
    protected function replace($url, $value)
    {
        return str_replace(urlencode("[PAGE]"), $value, $url);
    }
}