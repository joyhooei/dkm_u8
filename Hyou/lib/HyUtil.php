<?php

/**
 * 这个是工具类，无需实例化
 * @author wqm  QQ : 2508943091
 * @Description: 
 * Date : 2016-4-4 下午4:36:17
 */
class HyUtil
{
    private function __construct()
    {
        exit('工具类无法被实例化');
    }
    
    public function __clone() {
        return FALSE;
    }
    
    /**
     * 获取关键字信息，会过滤所有特殊字符，只留下 字母、-、数字
     * @param string $key
     * @param string $method        POST | GET | COOKIE
     */
    public static function getKeyWord($key, $method) {
        $val = self::_getGPC($key, $method);
        $val = preg_replace('/[^\w\-]/', '', $val);
        return $val;
    }
    
    
    /**
     * 返回 GET、POST、COOKIE 中键名为 $key 的值
     * @param string $key
     */
    public static function getStr($key) {
        $val = self::_getGPC($key);
        return self::_getVar($val);
    }
    
    private static function _getVar($val) {
        if (empty($val))
            return '';
        $val = htmlspecialchars($val);
        return addslashes($val);
    }
    
    /**
     * 获取GPC变量的值
     * @param string $key
     * @param string $method    GET | POST | COOKIE
     * @return string $var
     */
    private static function _getGPC($key, $method = '') {
        if (empty($key))
            return FALSE;
        if (!empty($method)) {
            $_key = '_'.$method;
            if (isset($GLOBALS[$_key][$key]))
                return $GLOBALS[$_key][$key];
            return FALSE;
        }
        
        if (isset($_POST[$key]))
            return $_POST[$key];
        if (isset($_GET[$key]))
            return $_GET[$key];
        if (isset($_COOKIE[$key]))
            return $_COOKIE[$key];
        return FALSE;
    }
    
}


