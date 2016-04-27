<?php
/**
 * 自接的支付回调处理
 * @author wqm  QQ : 2508943091
 * @Description: 
 * Date : 2016-4-7 上午10:13:29
 */
class Pay_Rsa_HyCallBack extends PayAdmin
{
    public function __construct() 
    {
        // 用 self::RSA_TYPE 类型实例化的，会在父类的构造方法里面进行数据的解密
        parent::__construct(self::RSA_TYPE);
        
        if (empty($this->decryed))
            exit(json_encode(array('code' => 1, 'msg' => 'data can not be decryed!')));
        
        // 这里获得的解密后的是一个可用于POST的参数数据，需要把该数据解析到 $this->data 中
        parse_str($this->decryed, $this->data);
        
        $this->fetchData();
    }
    
    
    
}
