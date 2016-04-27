<?php
/**
 * rsa 解密父类，涉及RSA解密操作的，一律通过继承此类来获取解密数据
 * POST 过来的数据key值为 game=xxxx&&data=xxxx ，需要 game 跟 data
 * 接口数据统一返回json数据 json_encode(array('code' => 1, 'msg'=>'xxx', 'ext(可为空)' => 'xxx'));
 * @author wqm  QQ : 2508943091
 * @Description: 
 * Date : 2016-4-5 上午8:54:22
 */
class PayAdmin
{
    const RSA_TYPE = 1;
    const ENCI_TYPE = 2;
    
    public $origin_data = '';       // 原始的请求数据
    public $decryed = FALSE;
    public $game = '';
    protected $data = array();
    
    public function __construct($type = self::RSA_TYPE) 
    {
        switch ($type) {
        	case self::RSA_TYPE:
        	   $this->constructRSA();
        	break;
        	case self::ENCI_TYPE:
        	    $this->constructENCI();
    	    break;
        	default:
        		exit('未定义的初始化方法');
        	break;
        }
    }
    
    /**
     * 用于实例化自接支付系统处理类，由我们客户端直接回调处理的
     */
    private function constructRSA()
    {
        $this->game = HyUtil::getKeyWord('game', 'POST');
        if (empty($this->game)) {
            echo json_encode(array('code' => 1, 'msg' => 'the posted key named "game" can not be empty!'));
            exit;
        }

        if (empty($GLOBALS['CONFIG']['hy_pay_key']))
            exit(json_encode(array('code' => 1, 'msg' => 'the rsa pub key must be set in pay.conf.php!')));
        
        $data = HyUtil::getStr('data', 'POST');
        loadPlugins('HyRsa');
        // 返回公钥解密的数据，该数据是url post请求的数据格式，需要处理 | false
        $this->decryed = HyRsa::decryptByStr($data, $GLOBALS['CONFIG']['hy_pay_key'], HyRsa::PUB_KEY);
    }
    
    /**
     * 实例化加密校验回调处理类，一般是第三方SDK服务器回调的处理类
     */
    private function constructENCI()
    {
        
        
    }
    
    
    
    
    
    /**
     * 用于发送发货请求数据至游戏服务端
     * @param array $orderData  用于发送到游戏服务端的数组
     */
    public function fetchPayData($orderData = NULL)
    {
        $orderData = empty($orderData) ? $this->data : $orderData;
        $erl_conf = array('game' => $this->game, 'platform' => $orderData['platform']
                    , 'zone_id' => $orderData['zone_id'], 'status' => $orderData['status']);
        $param = array();
        
        $param['order_id'] = array('b', $orderData['order_id']);
        $param['platform'] = array('i', $orderData['platform']);
        $param['zone_id'] = array('i', $orderData['zone_id']);
        $param['uid'] = array('b', $orderData['user_id']);
        $param['product_id'] = array('b', $orderData['product_id']);
        $param['amount'] = array('i', (float)$orderData['amount']);
        $param['num'] = array('i', (int)$orderData['num']);
        $param['type'] = array('i', $orderData['ext']);
        
        $ret = PaySysHelper::fetchSocket($erl_conf, array('m'=>'web_charge', 'args'=>$param));
        var_dump($ret);
        return $ret;
    }
    
    public function fetchData()
    {
        
    }
    
    
    
    
}

