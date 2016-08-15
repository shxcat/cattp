<?php
/**
 * 数据保存对象
 * Created by PhpStorm.
 * @package app\admin\extend
 * @version 16/8/15 下午5:19
 * @author  cbwfree
 */
namespace app\admin\extend;

use think\Controller;
use think\Db;
use think\Loader;
use think\Request;

/**
 * 数据保存对象
 * Class Save
 * @property bool batch 是否批量验证
 * @property string type 数据保存类型
 * @property string name 操作数据表
 * @property array|string|bool rule 数据验证规则名称
 * @property \Closure|null before 保存数据之前执行操作
 * @property \Closure|null after 保存数据之后执行操作
 * @package app\admin\extend
 */
class Save
{
    const INSERT = 'insert';
    const UPDATE = 'update';

    /**
     * @var null        单例对象
     */
    protected static $instance = null;

    /**
     * @var array       参数
     */
    protected $options = [
        'batch'     => false,       // 是否批量验证
        'type'      => '',          // 数据保存方式
        'name'      => '',          // 数据表名称
        'rule'      => true,        // 验证规则
        'before'    => null,        // 保存数据之前操作
        'after'     => null,        // 保存数据之后操作
    ];

    /**
     * 获取单例对象
     * @param array $options
     * @return Save|null
     */
    public static function instance($options = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($options);
        }
        return self::$instance;
    }

    /**
     * Save constructor.
     * @param $options
     */
    public function __construct($options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * 参数设置
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * 获取参数数据
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : null;
    }

    /**
     * 保存数据
     * @return mixed
     * @throws \think\Exception
     */
    public function submit()
    {
        $request = Request::instance();
        $data    = $request->post();        // 获取表单数据
        $log     = [];                      // 操作日志

        // 转换表单验证规则
        if ($this->rule === true) {
            $rule = Loader::parseName($this->name, 1);
        }

        // 数据校验
        if (! empty($rule) && $rule !== false) {
            if (is_array($rule) && isset($rule['rule']) && isset($rule['msg'])) {
                $msg    = $rule['msg'];
                $rule   = $rule['rule'];
            }else{
                $msg    = [];
            }
            $valid = $this->validate($data, $rule, $msg);
            if ($valid !== true) {
                return $valid;
            }
        }

        // 执行前置数据处理函数
        if ($this->before && is_callable($this->before)) {
            $result = call_user_func_array($this->before, [& $data, &$log]);

            // 前置数据处理出错
            if (! empty($result) && $result !== true) {
                return $result;
            }
        }

        if ($this->type == self::INSERT) {
            $lastId = Db::name($this->name)->insertGetId($data);
            if (! $lastId) {
                return '插入数据失败';
            }
        } else {
            $lastId = Db::name($this->name)->update($data);
        }

        //TODO 记录操作日志

        // 执行后置回调
        if ($this->after && is_callable($this->after)) {
            call_user_func_array($this->after, [ &$lastId, &$data ]);
        }

        return true;
    }

    /**
     * 表单验证
     * @param array $data
     * @param array|string $validate
     * @param array $message
     * @return bool
     */
    protected function validate($data, $validate, $message = [])
    {
        if (is_array($validate)) {
            $v = Loader::validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                list($validate, $scene) = explode('.', $validate);
            }
            $v = Loader::validate($validate);
            if (! empty($scene)) {
                $v->scene($scene);
            }
        }
        // 是否批量验证
        if ($this->batch) {
            $v->batch(true);
        }

        if (is_array($message)) {
            $v->message($message);
        }

        // 数据验证
        if (! $v->check($data)) {
            return $v->getError();
        } else {
            return true;
        }
    }
}