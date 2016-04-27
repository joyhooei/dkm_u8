<?php
/**
* 类文件说明
* @date: 2016年4月25日 上午9:32:30
* @author: max
* 统一U8接口地址
* 需要传递的参数 game_id, partner_id, session, uid
*/
class Auth_Admin
{
    
    public function __construct()
    {
        $db = DB::getInstance();
        
        $game_id = Jec::getInt('game_id');
        $partner_id = Jec::getInt('partner_id');
        
        if (empty($game_id) || empty($partner_id)) {
            exit(json_encode(array('state' => 2, 'data' => 'game_id can not be empty!')));
        }
        
        $row = DB::getInstance()->getRow("SELECT a.*, b.label FROM `dkm_partner_game_key` a LEFT JOIN `dkm_partner` b ON a.partner_id=b.partner_id where a.game_id='{$game_id}' AND a.partner_id='{$partner_id}';");
        if (empty($row))
            exit(json_encode(array('state' => 4, 'data' => 'channel conf is empty!')));
        
        // 注意，Auth_Channel_Label 需要统一返回结果
        $label = strtolower($row['label']);
        $channel_class = 'Auth_Channel_'.ucfirst($label);
        $class_file = str_replace('_', Hy_DS, $channel_class);
        
        if (!file_exists(MODULE_PATH.Hy_DS.$class_file.'.php')) {
            exit(json_encode(array('state' => 3, 'data' => 'class file not exists!')));
        }
        
        $row['session'] = Jec::getVar('session');
//         $row['sign'] = Jec::getVar('sign');
        $row['uid'] = Jec::getVar('uid');
        
        $auther = new $channel_class($row);
    }
    
}

