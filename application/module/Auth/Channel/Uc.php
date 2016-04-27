<?php
/**
* 类文件说明
* @date: 2016年4月22日 下午9:47:41
* @author: max
* 这个是 渠道标志 = UC 的处理类
*/
class Auth_Channel_Uc extends U8Admin
{
    const SUCCESS = 1;
    const REQ_ERROR = 10;
    
    public function __construct($row)
    {
        parent::__construct($row);
        
        $this->doAuth();
    }
    
    
    protected function genAuthCfg()
    {
        $body_arr = array(
            'id' => time(),
            'data' => array('sid' => $this->c_row['session']),
            'game' => array('gameId' => $this->c_row['app_id']),
            'sign' => $this->genSign(),
        );
        print_r($body_arr);
        return json_encode($body_arr);
    }
    
    protected function formatAuthRtn()
    {
        $rtn_data = json_decode($this->auth_ret, TRUE);
        
        if (self::SUCCESS == $rtn_data['state']['code']) {
            $ret_data = array(
                'state' => U8Admin::SUCCESS,
                'data' => array(
                    'userID' => dkmid,
                    'sdkUserID' => $rtn_data['data']['accountId'],
                    'username' => dkmname,
                    'sdkUserName' => $rtn_data['data']['nickName'],
                    'token' => $this->u8_tokey,
                ),
            );
        } else {
            $ret_data = array(
                'state' => $rtn_data['state']['code'],
            );
        }
            
        return json_encode($ret_data);
    }
    
    public function genSign()
    {
        return md5("{$this->c_row['session']}{$this->c_row['app_key']}");
    }
    
    
}


