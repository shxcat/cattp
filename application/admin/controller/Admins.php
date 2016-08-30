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
        $search->control('gender', Search::TYPE_SELECT, '性别', ['options' => AdminsModel::$attr['gender']]);
        $search->control('status', Search::TYPE_SELECT, '状态', ['options' => AdminsModel::$attr['status']]);

        // 创建查询条件
        $map    = $search->query();
        if ($this->request->get('group') == 'recycle') {
            $map['del_time'] = ['exp', 'is not null'];
        }

        $count  = AdminsModel::where($map)->count();
        $lists  = AdminsModel::where($map)->field('password,salt', true)->limit($paging->limit($count))->select();

        $this->assign("lists", $lists);
        $this->assign("group", [
            'recycle'   => '回收站',
        ]);
        return $this->fetch();
    }

    /**
     * 新增管理员
     * @return mixed
     */
    public function add()
    {
        $this->request->isPost() && $this->save(self::SAVE_INSERT);

        $this->assign("gender", AdminsModel::$attr['gender']);
        $this->assign("status", AdminsModel::$attr['status']);
        return $this->fetch('add');
    }

    /**
     * 编辑管理员
     * @param string|int $id
     * @return mixed
     */
    public function edit($id = '')
    {
        $this->request->isPost() && $this->save(self::SAVE_UPDATE);

        if (! $id) {
            $this->error("缺少ID");
        }
        if ($id == 1 && $id != $this->aid) {
            $this->error("超级管理员只有其自身可以修改");
        }


        $info = AdminsModel::get($id);
        if ( ! $info) {
            $this->error("没有找到相关数据");
        }

        $this->assign("info", $info->getData());
        return $this->add();
    }

    /**
     * 保存管理员数据
     * @param $type
     */
    protected function save($type)
    {
        $data   = $this->request->post();

        // 超管检查
        if ($type == self::SAVE_UPDATE && $data['id'] == 1 && $this->aid != $data['id']) {
            $this->error("您没有权限进行此操作!");
        }

        // 注册事件, 在写入数据库之前执行操作
        AdminsModel::event("before_write", function (AdminsModel $model) use ($type) {
            if ($type == self::SAVE_INSERT || ! empty($model->password)) {
                $model->salt = mt_salt();
                $model->password = gen_password($model->password, $model->salt);
            } else {
                unset($model->password);
            }
        });

        $model  = new AdminsModel;
        $update = $type == self::SAVE_INSERT ? false : true;
        $result = $model->validate(true)->allowField(true)->isUpdate($update)->save($data);

        if ($result === false) {
            $this->error($model->getError());
        }

        $this->success('管理员信息保存成功');
    }

    /**
     * 移动管理员到回收站
     * @param string $id
     */
    public function recycle($id = '')
    {
        if (! $id) {
            $this->error("缺少ID");
        }

        if ($id == 1) {
            $this->error("超级管理员不允许此操作");
        }

        $info = AdminsModel::get($id);
        if ( ! $info) {
            $this->error("没有找到相关数据");
        }

        // 删除数据
        $info->delete();

        $this->success("管理员帐号成功移到回收站");
    }

    /**
     * 删除管理员
     * @param string $id
     * @param int    $force
     */
    public function delete($id = '', $force = 0)
    {
        if (! $id) {
            $this->error("缺少ID");
        }

        if ($id == 1) {
            $this->error("超级管理员不允许此操作");
        }

        $info = AdminsModel::onlyTrashed()->find($id);
        if ( ! $info) {
            $this->error("没有找到相关数据");
        }

        // 删除数据
        $info->delete(true);

        $this->success("管理员永久删除成功");
    }

    /**
     * 恢复被软删除的数据
     * @param string $id
     */
    public function restore($id = '')
    {
        if (! $id) {
            $this->error("缺少ID");
        }

        if ($id == 1) {
            $this->error("超级管理员不允许此操作");
        }

        $info = AdminsModel::onlyTrashed()->find($id);
        if ( ! $info) {
            $this->error("没有找到相关数据");
        }

        $info->restore();

        $this->success("管理员信息恢复成功");
    }
}