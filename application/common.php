<?php
/**
 * 全局公共文件
 * Created by PhpStorm.
 * @author  cbwfree
 */

/**
 * 构建查询区间条件
 * @param int $start    起始数值
 * @param int $end      结束数值
 * @return array
 */
function build_map_between($start = 0, $end = 0){
    $map = null;
    if( $start > 0 && empty($end) ){
        $map = [">=", $start];
    }elseif( empty($start) && $end > 0 ){
        $map = ["<=", $end];
    }elseif( $start > 0 && $end > 0 ){
        $map = ["BETWEEN", [$start, $end]];
    }
    return $map;
}

/**
 * 构建时间范围查询条件
 * @param string|int $begin_time    开始时间
 * @param string|int $end_time      结束时间
 * @return array
 */
function build_map_time($begin_time = '', $end_time = '')
{
    $begin_time = ! is_numeric($begin_time) ? strtotime($begin_time) : $begin_time;
    $end_time = ! is_numeric($end_time) ? strtotime($end_time) : $end_time;
    return build_map_between($begin_time, $end_time);
}

/**
 * 安全转换时间戳
 * @param        $time
 * @param string $format
 * @return bool|string
 */
function f_date($time, $format = 'Y-m-d H:i:s')
{
    if (! is_numeric($time)) {
        $time = strtotime($time);
    }

    return $time > 0 ? date($format, $time) : '';
}


/**
 * 获取IP详情
 * @param string|array $ip IP地址
 * @param bool $region
 * @return array|null
 */
function get_ip_info($ip, $region = true)
{
    $ipObj = new \app\common\extend\Ip2Region();
    if (is_array($ip)) {
        $result = [];
        foreach($ip as $row){
            $result[$row] = $ipObj->btreeSearch($row);
            if($region){
                $result[$row] = $result[$row]['region'];
            }
        }
    } else {
        $result = $ipObj->btreeSearch($ip);
        if($region){
            $result = $result['region'];
        }
    }
    return $result;
}