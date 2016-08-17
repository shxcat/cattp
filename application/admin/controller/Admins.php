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
use app\admin\model\Admins as AdminsModel;

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
        $search = Search::instance();
        $paging = Paging::instance();

        $search->setQueryFields([
            'username'  => '用户名',
            'realname'  => '真实姓名',
            'mobile'    => '手机号码',
            'email'     => '邮箱地址',
        ]);
        $search->control('gender', Search::TYPE_SELECT, '性别', ['options' => $this->adminGender]);
        $search->control('status', Search::TYPE_SELECT, '状态', ['options' => $this->adminStatus]);

        // 创建查询条件
        $map    = $search->query();
        $count  = db("admins")->where($map)->count();
        $limit  = $paging->limit($count);
        $lists  = db("admins")->field('password,salt', true)->where($map)->limit($limit)->select();

        $this->assign("lists", $lists);
        $this->assign("gender", $this->adminGender);
        return $this->fetch();
    }

    /**
     * 新增管理员
     * @return mixed
     */
    public function add()
    {
        $this->request->isPost() && $this->save(self::SAVE_INSERT);

        $this->assign("gender", $this->adminGender);
        $this->assign("status", $this->adminStatus);
        return $this->fetch('add');
    }

    /**
     * 编辑管理员
     * @return mixed
     */
    public function edit()
    {
        $this->request->isPost() && $this->save(self::SAVE_UPDATE);

        return $this->add();
    }

    /**
     * 保存管理员数据
     * @param $type
     */
    protected function save($type)
    {
        $data   = $this->request->post();
        $result = $this->validate($data, 'admins');

        if ($result !== true) {
            $this->error($result);
        }

        if ($type == self::SAVE_INSERT) {
            // 创建密码
            $data['salt']       = mt_salt();
            $data['password']   = gen_password($data['password'], $data['salt']);
            $data['add_time']   = time();

            $last_id = db("Admins")->insert($data);
            if (! $last_id) {
                $this->error("添加管理员失败");
            }

            $this->success('添加管理员成功');
        } else {
            db("Admins")->update($data);

            $this->success('管理员信息更新成功');
        }
    }
}