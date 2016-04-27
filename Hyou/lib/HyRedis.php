<?php

class HyRedis extends Redis
{
    const ORIGIN = 1;
    const ORDER = 2;
    const SAVE = 3;
    
    private static $instance = NULL;
    
    private static $origin_instance = 'ORIGIN_instance';  // 原始订单队里
    private static $order_instance = 'ORDER_instance'; // 订单队列, 用于推送到游戏端进行处理
    private static $save_instance = 'SAVE_instance';  // 保存队列, 用于将数据持久化到mysql
    
    public function __construct()
    {
        $this->connect($GLOBALS['CONFIG']['redis']['host'], $GLOBALS['CONFIG']['redis']['port']);
        $this->auth('helloworld');
    }
    
    
    public static function getInstance()
    {
        if (self::$instance)
            return self::$instance;
        self::$instance = new HyRedis();
        return self::$instance;
    }
    
    /**
     * 设置一个值到 redis 中
     * @param string $key
     * @param string $val
     * @param int $time_out     默认为 0 表示永久存在，>0 则表示存在的时效
     */
    public function saveTTL($key, $val, $time_out = 0)
    {
        return  empty($time_out) ? self::$instance->set($key, $val) : self::$instance->setex($key, $time_out, $val);
    }
    
    
    
    
    /**
     * 根据 $type 类型返回对应的队列名称
     * @param unknown $prefix
     * @param unknown $type
     * @return string|boolean
     */
    public static function geninstanceName($prefix, $type = self::ORIGIN)
    {
        switch ($type) {
        	case self::ORIGIN:
        	    return $prefix.self::$origin_instance;
        	    break;
        	case self::ORDER:
        	    return $prefix.self::$origin_instance;
        	    break;
        	case self::SAVE:
        	    return $prefix.self::$save_instance;
        	    break;
        	default:
        	    return FALSE;
        	    break;
        }
    }
    
}



