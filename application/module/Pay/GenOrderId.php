<?php
/**
* 类文件说明
* @date: 2016年4月29日 下午8:09:31
* @author: max
* 生成订单号
*/
class Pay_GenOrderId
{
    private $redis = NULL;
    private $db = NULL;
    public function __construct()
    {
        $this->redis = HyRedis::getInstance();
        $this->db = DB::getInstance();
        $args = Helper::getSDKArgs();
        $order_id = $this->genOrder();
        
        $order_row = array(
            'order_id' => $order_id,
            'dkm_uid' => $args['userID'],
            'product_id' => $args['productId'],
            'product_name' => $args['productName'],
            'product_desc' => $args['productDesc'],
            'money' => (float)($args['money'] / 100),   // money 传过来是 分，数据库存放的是 元
            'role_id' => $args['roleID'],
            'role_name' => $args['roleName'],
            'server_id' => $args['serverID'],
            'server_name' => $args['serverNames'],
            'ext' => $args['extension'],
            'sign' => $args['sign'],
            'ctime' => TIME,
        );
        
        $this->db->insert('dkm_order', $order_row);
        
        exit(json_encode(array('state' => SUCCESS, 'data' => array('orderId' => $order_id, 'extension' => $args['extension']))));
        
    }
    
    
    private function genOrder()
    {
        $order_id = date('Y') - 2015; // 基数
        $month = date('n');
        $day = date('j');
        $hour = date('G');
        $mins = (int)date('i');
        $sec = (int)date('s');
        
        $z = $this->redis->incr('u8_order_id_inc');
        $inc = $z > 10000 ? $this->redis->set('u8_order_id_inc', 0) : $z;  
        
        $order_id = (float)$order_id;
        $order_id = $order_id << 4 | $month;
        $order_id = $order_id << 5 | $day;
        $order_id = $order_id << 5 | $hour;
        $order_id = $order_id << 6 | $mins;
        $order_id = $order_id << 6 | $sec;
        $order_id = $order_id << 32 | $inc;
        
        return (string)$order_id;
    }
    
    
}


