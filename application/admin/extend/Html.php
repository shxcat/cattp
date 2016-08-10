<?php
/**
 * 自定义标签库
 * Created by PhpStorm.
 * @package app\admin\extend
 * @version 16/8/1 下午5:09
 * @author  cbwfree
 */
namespace app\admin\extend;

use think\template\TagLib;

/**
 * 自定义标签库
 * Class Html
 * @package app\admin\extend
 */
class Html extends TagLib
{
    /**
     * @var array   定义标签列表
     *              标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
     */
    protected $tags   =  [
        'header'    => ['attr' => 'title,if,else'],
        'select'    => ['attr' => 'name,options,selected,']
    ];

    /**
     * @param $tag
     * @param $content
     * @return string
     */
    public function tagHeader($tag, $content)
    {
        $title  = empty($tag['title']) ? '' : $tag['title'];
        $if     = (! isset($tag['if']) || empty($tag['if'])) ? '' : $tag['if'];
        $else   = (! isset($tag['else']) || empty($tag['else'])) ? '' : $tag['else'];

        $parse = '<div id="page-header" class="am-cf">';
        $parse.= '<h1 class="title"><i class="am-icon-bookmark"></i>&nbsp;';

        // 支持用函数传数组
        if (strpos($title, ":") === 0) {
            $var  = '$_' . uniqid();
            $title = $this->autoBuildVar($title);
            $parse.= $var . '=' . $title . '; ';
            $title = $var;
        } elseif (strpos($title, "$") === 0) {
            $title = $this->autoBuildVar($title);
        }

        if ($if) {
            $parse.= '<?php if( '.$if.' ): ?>';
            $parse.= $title;
            $parse.= '<?php else: ?>';
            $parse.= $else;
            $parse.= '<?php endif; ?>';
        } else {
            $parse.= $title;
        }

        $parse.= '</h1><div class="button">';
        $parse.= $content;
        $parse.= '</div></div>';
        return $parse;
    }

}