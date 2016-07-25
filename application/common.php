<?php
/**
 * 全局公共文件
 * Created by PhpStorm.
 * @author  cbwfree
 */

if (! function_exists('get_client_ip')) {
    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    function get_client_ip($type = 0, $adv = false) {
        $type       =  $type ? 1 : 0;
        static $ip  =   null;
        if ($ip !== null) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? [$ip, $long] : ['0.0.0.0', 0];
        return $ip[$type];
    }
}


if (! function_exists('get_ip_info')) {
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
}