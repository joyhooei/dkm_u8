<?php
/**
* 类文件说明
* @date: 2016年4月26日 上午10:45:57
* @author: max
* CP 端token校验处理, 需要的参数是 52wan平台的 game_id、session
* 加密规则为 md5(密钥 + session)
*/

class Auth_AuthCheck
{
    
    public function __construct()
    {
        $session = Jec::getVar('session');
        $user_info = HyRedis::getInstance()->get($session);
        if ($user_info)
            exit(json_encode(array('state' => SUCCESS, 'data' => unserialize($user_info))));
        exit(json_encode(array('state' => TOKEN_FAILE, 'data' => 'invalid session!')));
    }
    
}

