<?php
/**
* 类文件说明
* @date: 2016年4月22日 下午9:53:53
* @author: max
* 52wan平台账号辅助类
*/
class Auth_AuthHelper
{
    const SESSION_TAB_NAME = 'DKM_SESSION_TAB';     // 多可梦会话保存表

    /**
     * 返回52wan平台的玩家账号
     * @param array $ret
     * @return array $dkm_user
     */
    public static function createDKMUser($ret)
    {
        $db = DB::getInstance();
        $dkm_user = $db->getRow("select * from dkm_account where partner_uid='{$ret['partner_uid']}' and partner_id='{$ret['partner_id']}'");
        if (!empty($dkm_user['id'])) {
            return $dkm_user;
        }
        
        // TODO, 这里创建DKM玩家账号，待完善
        $dataArr = array(
            'gameId' => $ret['game_id'],
            'device' => 1,
            'serverId' => 102,
            'channelId' => $ret['partner_id'],
            'sdkJson' => array(
                'username' => $ret['partner_uid'].DKM_NAME_SUFFIX,  //  平台那边的 uid 作为52wan平台的 username
                'password' => DKM_DEFAULT_PWD,
                'add_type' => 'username',   //表明是通过帐号方式注册
            )
        );
        $dataArr['jqgame_sign'] = self::getSign($dataArr, DKM_SECRET_KEY);
//         print_r($dataArr);
        $dkm_rtn = Net::fetch(DKM_CREATE_USER_URL, array(CURLOPT_POST => TRUE, CURLOPT_POSTFIELDS => json_encode($dataArr)));
        $dkm_rtn = json_decode($dkm_rtn, TRUE);
        if (0 == (int)$dkm_rtn['code']) {
            $db->insert('dkm_account', array('partner_uid' => $ret['partner_uid'], 'partner_uname' => $ret['partner_uname'],
                'partner_id' => $ret['partner_id'], 'game_id' => $ret['game_id'], 
                'dkm_uid' => $dkm_rtn['userInfo']['userId'], 'dkm_uname' => $dkm_rtn['userInfo']['username'],
                'ctime' => TIME,
            ));
            
            return array(
                'partner_uid' => $ret['partner_uid'],
                'partner_uname' => $ret['partner_username'],
                'dkm_uid' => $dkm_rtn['userInfo']['userId'],
                'dkm_uname' => $dkm_rtn['userInfo']['username'],
            );
        }
        
        return FALSE;
    } 
    
    public static function getSign(array $data, $secrectKey){
        $str = "gameId={$data['gameId']}&channelId={$data['channelId']}&device={$data['device']}&username={$data['sdkJson']['username']}&password={$data['sdkJson']['password']}{$secrectKey}";
        return md5($str);
    }
    
    /**
     * 生成U8的session信息用于自己的登陆校验
     * @param array $c_row
     * @param array $user_info
     * @return string $token
     */
    public static function genSession($c_row, $user_info)
    {
        $token = md5("{$c_row['partner_id']}_{$c_row['game_id']}".microtime());
        HyRedis::getInstance()->saveTTL($token, serialize($user_info), SESSION_TTL);
        return $token;
    }
    
    
}

