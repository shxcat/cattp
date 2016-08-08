<?php
/**
 * 管理员管理控制器
 * Created by PhpStorm.
 * @package app\admin\controller
 * @version 16/7/29 下午5:30
 * @author  cbwfree
 */
namespace app\admin\controller;

use app\admin\extend\Paging;
use app\admin\extend\Search;

/**
 * 管理员管理控制器
 * Class Admins
 * @package app\admin\controller
 */
class Admins extends Auth
{

    /**
     * 管理员列表
     * @return mixed
     */
    public function lists()
    {
        $search = Search::instance();
        $search->setFields([
            'username'  => '用户名',
            'realname'  => '真实姓名',
        ]);
        $search->control('username', Search::TYPE_SEARCH, '用户名', ['icon' => 'am-icon-user']);
        $search->control('time', Search::TYPE_DATETIME, '注册时间', ['range' => true]);
        $search->control('username', Search::TYPE_SELECT2, '选择', ['remote' => url('ajax'), 'width' => '100px']);

        $paging = Paging::instance();

        $paging->limit(1000);

        $this->assign("paging", $paging->html());
        $this->assign("search", $search->html());
        return $this->fetch();
    }

    /**
     * 新增管理员
     * @return mixed
     */
    public function add()
    {

        return $this->fetch();
    }
}