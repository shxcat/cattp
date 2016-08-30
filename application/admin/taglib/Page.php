<?php
/**
 * 页面模版标签库
 * Created by PhpStorm.
 * @package app\admin\taglib
 * @version 16/8/1 下午5:09
 * @author  cbwfree
 */
namespace app\admin\taglib;

use think\Request;
use think\Url;
use think\template\TagLib;

/**
 * 页面模版标签库
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
        'pos'       => ['attr' => 'label,link', 'close' => 0, 'alias' => 'position'],
        'header'    => ['attr' => 'title,small,group,page,field'],
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
        $tools = isset($tag['tools']) ? (bool) $tag['tools'] : true;

        $parse = '<ol id="location" class="am-breadcrumb am-breadcrumb-slash">';
        $parse.= '<li><a href="'.Url::build('admin/index/index').'" class="am-icon-home" data-pjax>首页</a></li>';
        $parse.= $content;

        if ($tools) {
            $parse.= '<li class="am-fr"><a href="javascript:;" onclick="$.AMUI.pjax.reload();" title="刷新"><i class="am-icon-refresh"></i>刷新(F5)</a></li>';
            $parse.= '<li class="am-fr"><a href="javascript:;" onclick="window.open($.AMUI.pjax.location());" title="新窗口打开"><i class="am-icon-external-link"></i>新窗口打开</a></li>';
        }

        $parse.= '</ol>';

        return $parse;
    }

    /**
     * 导航定位
     * @param $tag
     * @return string
     */
    public function tagPos($tag)
    {
        $label  = $tag['label'];
        $link   = isset($tag['link']) ? $tag['link'] : '';

        $parse = '<li>';
        if (strpos($label, "$") === 0) {
            $label = '<?php echo '.$this->autoBuildVar($label).'; ?>';
        } elseif (strpos($label, ":") === 0) {
            $var = '$_' . uniqid();
            $label = '<?php ' . $var . '=' . $this->autoBuildVar($label) . '; echo ' . $var . '; ?>';
        }

        if ($link) {
            $parse.= '<a href="'.Url::build($link).'">'.$label.'</a>';
        } else {
            $parse.= $label;
        }

        $parse.= '</li>';

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
        $small = isset($tag['small']) ? $tag['small'] : '';
        $group = isset($tag['group']) ? $tag['group'] : '';
        $page  = isset($tag['page']) ? $tag['page'] : '';

        $parse = '<div id="page-header" class="am-cf">';
        $parse.= '<h1 class="title"><i class="am-icon-bookmark"></i>&nbsp;';

        // 支持用函数传数组
        $flag = substr($title, 0, 1);
        if (":" == $flag || "$" == $flag) {
            $title = '<?php echo '.$this->autoBuildVar($title).'; ?>';
        }

        if (! empty($group)) {
            // page变量
            if ($page) {
                $flag = substr($page, 0, 1);
                if (":" == $flag || "$" == $flag) {
                    $page = $this->autoBuildVar($title);
                } else {
                    $page = '\'' . $page . '\'';
                }
            } else {
                $page = '\think\Request::instance()->action()';
            }

            $parse.= '<a href="<?php echo \think\Url::build(' . $page . '); ?>" data-pjax>' . $title . '</a>';
        } else {
            $parse.= $title;
        }

        // 小标题
        if ($small) {
            $flag = substr($small, 0, 1);
            if (":" == $flag || "$" == $flag) {
                $small = '<?php echo '.$this->autoBuildVar($small).'; ?>';
            }

            $parse.= '<small>'.$small.'</small>';
        }

        $parse.= '</h1>';

        if (! empty($group)) {
            $field = isset($tag['field']) ? $tag['field'] : 'group';

            // 列表分组处理
            $flag = substr($tag['group'], 0, 1);
            if (":" == $flag) {
                $var = '$_' . uniqid();
                $parse.= '<?php ' . $var . '=' . $this->autoBuildVar($group) . '; ?>';
                $group = $var;
            }

            $parse.= '<div class="badge">';
            $parse.= '<?php $_group_value = \think\Request::instance()->get(\''.$field.'\');';
            $parse.= 'foreach('.$group.' as $k => $v): ';
            $parse.= '$_active = ($_group_value != "" && $_group_value == $k ? " am-badge-success" : ""); ?>';
            $parse.= '<a href="<?php echo \think\Url::build('.$page.', [\''.$field.'\' => $k]); ?>" class="am-badge<?php echo $_active; ?>" data-pjax>';
            $parse.= '<?php echo $v; ?></a>&nbsp;<?php endforeach; ?></div>';
        }

        $parse.= '<div class="button">';
        $parse.= $content;
        $parse.= '</div></div>';
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
            $parse = '<?php echo '.$this->autoBuildVar($name).'->html(); ?>';
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
            $parse = '<?php echo '.$this->autoBuildVar($name).'->html(); ?>';
        } else {
            $parse = '<?php echo \app\admin\extend\Search::instance("'.$name.'")->html(); ?>';
        }

        return $parse;
    }

}