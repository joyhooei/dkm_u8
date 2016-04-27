<?php
/**
* 类文件说明
* @date: 2016年4月22日 下午9:47:41
* @author: max
* 这个是 渠道标志 = 52wan 的处理类
*/
class Auth_Channel_C52wan extends U8Admin
{
    // 各个类定义的成功值
    const SUCCESS = 0;
    const REQ_ERROR = 10;
    
    public function __construct($row)
    {
        parent::__construct($row);
        
        $this->doAuth(TRUE);
    }
    
    // 格式化各个渠道不同的请求参数
    public function genAuthCfg()
    {
        $body_arr = array(
            'gameId' => $this->c_row['app_id'], // 联运那边分配的游戏Id
            'device' => 1,       //对应的设备编号
            'serverId' => 102,   //对应的服务器Id
            'channelId' => $this->c_row['partner_id'],   //对应的渠道Id
            'sdkJson' => array(
                'username' => $this->c_row['uid'],//要验证token的用户名
                'token' => $this->c_row['session'] //要验证的token
            ),
//             'jqgame_sign' => $this->genSign(),
        );
        $body_arr['jqgame_sign'] = $this->genSign($body_arr, $this->c_row['secret_key']);
//         print_r($body_arr);exit;
        return json_encode($body_arr);
    }
    
    /**
     * 将联运的账号转化为统一的结果，并赋值给 $this->auth_ret
     * @see U8Admin::formatAuthRtn()
     */
    public function formatAuthRtn()
    {
        // u8_ret 必须有 game_id, partner_id, partner_uid 
        $this->auth_ret = json_decode($this->auth_ret, TRUE);
        if (self::SUCCESS == (int)$this->auth_ret['code']) {
            $this->u8_ret = array(
                'game_id' => $this->c_row['game_id'],
                'partner_uid' => $this->auth_ret['uid'],
                'partner_id' => $this->c_row['partner_id'],
                'partner_uname' => $this->auth_ret['uid'],
            );
        } else {
            exit(json_encode(array('state' => TOKEN_FAILE, 'data' => 'invalid token!')));
        }
//         print_r($ret_data);exit;
    }
    
    public function genSign(array $data, $secrectKey)
    {
        $str = "gameId={$data['gameId']}&channelId={$data['channelId']}&serverId={$data['serverId']}&device={$data['device']}{$secrectKey}";
        return md5($str);
    }
    
    
}


