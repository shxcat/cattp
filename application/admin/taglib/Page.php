<?php
/**
 * 页面模版标签库
 * Created by PhpStorm.
 * @package app\admin\taglib
 * @version 16/8/1 下午5:09
 * @author  cbwfree
 */
namespace app\admin\taglib;

use think\template\TagLib;

/**
 * 自定义标签库
 * Class Page
 * @package app\admin\taglib
 */
class Page extends TagLib
{
    /**
     * @var array   定义标签列表
     *              标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
     */
    protected $tags = [
        'location'  => ['attr' => 'tools', 'expression' => true],
        'pos'       => ['attr' => 'label,link', 'close' => 0],
        'header'    => ['attr' => 'title,if,else'],
        'paging'    => ['attr' => 'name', 'close' => 0, 'expression' => true],
        'search'    => ['attr' => 'name', 'close' => 0, 'expression' => true],
    ];

    /**
     * @param $tag
     * @param $content
     * @return string
     */
    public function tagLocation($tag, $content)
    {
//        $location = [
//            '<ol id="location" class="am-breadcrumb am-breadcrumb-slash">',
//            '<li><a href="'.url('admin/index/index').'" class="am-icon-home" data-pjax>首页</a></li>'
//        ];
//        foreach($path as $key => $val){
//            if( is_string($key) ){
//                $location[] = '<li><a href="'.url($key).'" data-pjax>'.$val.'</a></li>';
//            }else{
//                $location[] = '<li>'.$val.'</li>';
//            }
//        }
//
//        if ($tools) {
//            $location[] = '<li class="am-fr"><a href="javascript:;" onclick="$.AMUI.pjax.reload();" title="刷新"><i class="am-icon-refresh"></i>刷新(F5)</a></li>';
//            $location[] = '<li class="am-fr"><a href="javascript:;" onclick="window.open($.AMUI.pjax.location());" title="新窗口打开"><i class="am-icon-external-link"></i>新窗口打开</a></li>';
//        }
//
//        $location[] = '</ol>';
//        return implode("", $location);
        $parse = '<?php '.$content.' ?>';

        return $parse;
    }

    /**
     * 导航定位
     * @param $tag
     * @return string
     */
    public function tagPos($tag)
    {
        $name = isset($tag['name']) ? $tag['name'] : '';

        if (strpos($name, "$") === 0) {
            $var = '$_' . uniqid();
            $name = $this->autoBuildVar($name);
            $parse = '<?php '. $var . ' = ' . $name . '; echo '.$var.'->html(); ?>';
        } else {
            $parse = '<?php echo \app\admin\extend\Paging::instance("'.$name.'")->html(); ?>';
        }

        return $parse;
    }

    /**
     * 页面头部
     * @param $tag
     * @param $content
     * @return string
     */
    public function tagHeader($tag, $content)
    {
        $title = empty($tag['title']) ? '' : $tag['title'];
        $if = (!isset($tag['if']) || empty($tag['if'])) ? '' : $tag['if'];
        $else = (!isset($tag['else']) || empty($tag['else'])) ? '' : $tag['else'];

        $parse = '<div id="page-header" class="am-cf">';
        $parse .= '<h1 class="title"><i class="am-icon-bookmark"></i>&nbsp;';

        // 支持用函数传数组
        if (strpos($title, ":") === 0) {
            $var = '$_' . uniqid();
            $title = $this->autoBuildVar($title);
            $title = '<?php ' . $var . '=' . $title . '; echo '.$var.'; ?>';
        } elseif (strpos($title, "$") === 0) {
            $title = $this->autoBuildVar($title);
            $title = '<?php echo '.$title.'; ?>';
        }

        if ($if) {
            $parse .= '<?php if( ' . $if . ' ): ?>';
            $parse .= $title;
            $parse .= '<?php else: ?>';
            $parse .= $else;
            $parse .= '<?php endif; ?>';
        } else {
            $parse .= $title;
        }

        $parse .= '</h1><div class="button">';
        $parse .= $content;
        $parse .= '</div></div>';
        return $parse;
    }

    /**
     * 获取分页内容
     * @param $tag
     * @return string
     */
    public function tagPaging($tag)
    {
        $name = isset($tag['name']) ? $tag['name'] : '';

        if (strpos($name, "$") === 0) {
            $var = '$_' . uniqid();
            $name = $this->autoBuildVar($name);
            $parse = '<?php '. $var . ' = ' . $name . '; echo '.$var.'->html(); ?>';
        } else {
            $parse = '<?php echo \app\admin\extend\Paging::instance("'.$name.'")->html(); ?>';
        }

        return $parse;
    }

    /**
     * 获取搜索框内容
     * @param $tag
     * @return string
     */
    public function tagSearch($tag)
    {
        $name = isset($tag['name']) ? $tag['name'] : '';

        if (strpos($name, "$") === 0) {
            $var = '$_' . uniqid();
            $name = $this->autoBuildVar($name);
            $parse = '<?php '. $var . ' = ' . $name . '; echo '.$var.'->html(); ?>';
        } else {
            $parse = '<?php echo \app\admin\extend\Search::instance("'.$name.'")->html(); ?>';
        }

        return $parse;
    }

}