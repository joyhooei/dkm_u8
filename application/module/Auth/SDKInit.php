<?php
/**
* 类文件说明
* @date: 2016年4月29日 下午2:47:38
* @author: max
* 用于SDK初始化的信息返回
* 请求参数: args 为 game_id, partner_id,  安卓的imei 跟 IOS的uuid 统一为 uuid, mac, sdk_ver, ip, os_type (1：安卓， 2：IOS)
* 以json格式拼装并进行  aes 加密之后，携带在 args 里面传过来
*/
class Auth_SDKInit
{
    private $C52wan = 250250;
    public function __construct()
    {
        $args = Helper::getSDKArgs();
        
        if (empty($args))
            exit(json_encode(array('state' => ENCRYPT_ERROR, 'error_msg' => 'encrypt error!')));
        if (empty($args['game_id']))
            exit(json_encode(array('state' => EMPTY_GID, 'error_msg' => 'game_id is empty!')));
        $db = DB::getInstance();
        // 生成设备唯一标识的md5码
        $device_id = md5("{$args['uuid']}{$args['mac']}");
        // 判断是否存在对应表
        $tab = $db->getRow("SHOW TABLES LIKE 'game_device_{$args['game_id']}';", MYSQLI_NUM);
        if (empty($tab[0]))
            exit(json_encode(array('state' => EMPTY_GID, 'error_msg' => 'game_id is empty!')));
        
        
        $ip_row = IpLocationZh::find('113.67.226.68');
//         $ip_row = IpLocationZh::find($args['ip']);   // 请求的IP
        $ip_area = $ip_row[1].$ip_row[2];
        $area = $db->getOne("SELECT CONCAT(`province`,`city`) area FROM `game_device_1` WHERE device_id='{$device_id}';");
        if (empty($area)) { // 新增一条记录
            $data = array('device_id' => $device_id, 'login_ip' => $args['ip'], 'game_id' => $args['game_id'],
                'partner_id' => $args['partner_id'], 'sdk_ver' => $args['sdk_ver'],
                'ip_province' => $ip_row[1], 'ip_city' => $ip_row[2],
                'province' => $ip_row[1], 'city' => $ip_row[2]
            );
            $db->insert("game_device_{$args['game_id']}", $data);
        }
        
        if ($ip_area == $area) { // 切换为52wan的SDK;
            $row = $db->getRow("select * from dkm_partner_game_key where partner_id='{$this->C52wan}' and game_id='{$args['game_id']}'");
            echo json_encode(array('state' => SUCCESS, 'data' => array('flag' => TRUE, 'sdk_info' => $row)));
            exit;
        }
        // 返回联运的参数
        $row = $db->getRow("select * from dkm_partner_game_key where partner_id='{$args['partner_id']}' and game_id='{$args['game_id']}'");
        echo json_encode(array('state' => SUCCESS, 'data' => array('flag' => FALSE, 'sdk_info' => $row)));
        exit;
    }
    
}

