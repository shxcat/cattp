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

    protected $adminStatus = [1 => '正常', 0 => '锁定'];
    protected $adminGender = ['保密', '男', '女'];

    /**
     * 管理员列表
     * @return mixed
     */
    public function lists()
    {
        $search = Search::instance(['tiled' => false]);
        $paging = Paging::instance();

        $search->setQueryFields([
            'username'  => '用户名',
            'realname'  => '真实姓名',
            'mobile'    => '手机号码',
            'email'     => '邮箱地址',
        ]);
        $search->control('sex', Search::TYPE_SELECT, '性别', ['options' => $this->adminGender]);
        $search->control('st', Search::TYPE_SELECT, '状态', ['options' => $this->adminStatus]);

        // 创建查询条件
        $map = $search->query([
            'sex'   => 'gender',
            'st'    => 'status'
        ]);

        $count = db("admins")->where($map)->count();
        $lists = db("admins")->field('password,salt', true)->where($map)->limit($paging->limit($count))->select();

        $this->assign("lists", $lists);
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