<?php
/**
* 类文件说明
* @date: 2016年4月25日 上午9:07:26
* @author: max
*
*/
class U8Admin
{
    const SUCCESS = 1;
    const ARG_ERROR = 2;
    const NO_LOGIN = 3;
    const SRV_ERROR = 4;
    
    // 请求对应的appkey之类的参数数组
    public $c_row = array();    // array('partner_id', 'game_id', 'app_id', 'app_key', 'secret_key', 'auth_url', 'pay_url', 'ext_url');
    protected $auth_ret = array();
    protected $u8_ret = array();    // u8 统一的返回结果
    protected $u8_token = '';       // u8 的登陆 token
    
    public function __construct($row)
    {
        $this->c_row = $row;
    }
    
    /**
     * 统一处理所有渠道的请求
     * @param boolean $is_post      是否使用 POST 方式请求数据
     * @return array $rtn
     */
    public function doAuth($is_post = FALSE)
    {
        $args = $this->genAuthCfg();
        if ($is_post) {
            $this->auth_ret = Net::fetch($this->c_row['auth_url'], array(CURLOPT_POST => TRUE, CURLOPT_POSTFIELDS => $args));
        } else {
            $url = $this->c_row['auth_url'].'&'.$args;
            $this->auth_ret = Net::fetch($url);
        }

        $this->formatAuthRtn(); // 统一渠道返回值，赋值到 $u8_ret，如果失败，则中断之后的执行并输出

        // u8_ret 必须有 game_id, partner_uid, partner_uid 
        $dkm_user = Auth_AuthHelper::createDKMUser($this->u8_ret);
//         echo '=============';
//         print_r($dkm_user);exit;
        if ($dkm_user) {
            $this->doAuthExt();
            $rsp_data = array(
                'state' => self::SUCCESS,
                'data' => array(
                    'userID' => $dkm_user['dkm_uid'],
                    'username' => $dkm_user['dkm_uname'],
                    'sdkUserID' => $dkm_user['partner_uid'],
                    'sdkUserName' => $dkm_user['partner_uname'],
//                     'token' => Auth_AuthHelper::genSession($this->c_row),
                ),
            );
            $rsp_data['data']['token'] = Auth_AuthHelper::genSession($this->c_row, $rsp_data['data']);
            echo json_encode($rsp_data);
        }
        return ;
    }
    
    /**
     * 子类需要各自实现这个方法，进行各个渠道定制化的请求参数
     * 是url的字符串或json字符串
     * @return string $data
     */
    protected function genAuthCfg()
    {
        exit(json_encode(array('state' => 5, 'data' => 'can not use the parent method: genAuthCfg')));
    }
    
    /**
     * 子类需要各自实现这个方法，将各个渠道返回的信息统一转为52wan的结果
     * 会为 $u8_ret 赋值
     */
    protected function formatAuthRtn()
    {
        exit(json_encode(array('state' => 5, 'data' => 'can not use the parent method: formatAuthRtn')));
    }
    
    /**
     * 做一些登陆成功的额外操作，例如UC要上传玩家等级什么的，这个方法选择性处理
     * @return bool
     */
    protected function doAuthExt()
    {
        
    }
    
    
    /**
     * 用于统一进行支付处理
     */
    public function doPay()
    {
        $args = $this->genPayCfg();
        if ($is_post) {
            $ret = Net::fetch($args['pay_url'], array(CURLOPT_POST => TRUE, CURLOPT_POSTFIELDS => $args));
        } else {
            $url = $args['auth_url'].'&'.$args;
            $ret = Net::fetch($url);
        }
        var_dump($ret);
        return $this->formatPayRtn();
        exit;
    }
    
    protected function genPayCfg()
    {
        
    }
    
    protected function formatPayRtn()
    {
        
        
    }
    
    /**
     * 用于支付成功之后的额外处理，子类可看情况重写
     * @return bool
     */
    protected function doPayExt()
    {
        
    }
    
    
}