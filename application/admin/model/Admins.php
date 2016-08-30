<?php
/**
 * Admins 数据模型
 * Created by PhpStorm.
 * @package app\admin\model
 * @version 16/8/17 下午3:15
 * @author  cbwfree
 */
namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

/**
 * Admins 数据模型
 * Class Admins
 * @package app\admin\model
 */
class Admins extends Model
{
    use SoftDelete;

    // 软删除字段
    protected static $deleteTime = 'del_time';

    // 新增数据自动完成
    protected $insert = ['add_time'];

    public static $attr = [
        'status'    => [1 => '正常', 0 => '锁定'],
        'gender'    => ['保密', '男', '女'],
    ];

    /**
     * 设置添加时间
     * @return int
     */
    public function setAddTimeAttr()
    {
        return time();
    }

    /**
     * 获取状态文本
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getStatusTextAttr($value, $data)
    {
        return self::$attr['status'][$data['status']];
    }

    /**
     * 获取性别文本
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getGenderTextAttr($value, $data)
    {
        return self::$attr['gender'][$data['gender']];
    }
}