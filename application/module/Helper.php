<?php
/**
* 类文件说明
* @date: 2016年4月29日 下午8:34:35
* @author: max
* 全局辅助静态类
*/
class Helper
{
    
    private function __construct()
    {
        
    }
    
    /**
     * 统一解密数据方法
     * @return array $args      返回SDK请求携带的参数数组
     */
    public static function getSDKArgs()
    {
        $args = Jec::getVar('args');
        
        HyCrypt::setKey(AES_SECRET_KEY);
        HyCrypt::setIv(AES_IV_KEY);
        $args = HyCrypt::aesDecrypt($args);
        $args = json_decode($args, TRUE);
        
        return $args;
    }
    
    
    
}


