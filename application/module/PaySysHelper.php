<?php
/**
 * paymen_sys 辅助类，简写为 PaySysHelper
 * 主要用于向服务端请求数据
 * @author wqm  QQ : 2508943091
 * @Description: 
 * Date : 2016-4-5 上午11:13:54
 */
class PaySysHelper
{
    private static $cache_ip = NULL;
    private static $cache_port = NULL;
    
    private function __construct() {
        echo 'init the PsHelper!';
    }
    
    /**
     * 根据 $erl_conf 设置IP、端口这些信息
     * 数组格式必须为array('game' => '', 'platform' => '', 'zone_id' => 1, 'status' => 'test|audit|stable')，status 为空则默认为 stable
     * @param array $erl_conf
     */
    private function _genIpPort($erl_conf) {
        if (self::$cache_ip && self::$cache_port)
            return TRUE;
        require_once CONFIG_PATH.'/'.$erl_conf['game'].'/'.$erl_conf['platform'].'.port.php';
        $_key = empty($erl_conf['status']) ? "{$erl_conf['zone_id']}-stable" : "{$erl_conf['zone_id']}-{$erl_conf['status']}";
        self::$cache_ip = $GLOBALS['ERL_CONFIG'][$_key]['ip'];
        self::$cache_port = $GLOBALS['ERL_CONFIG'][$_key]['web_port'];
    }
    
    /**
     * 封装下接口，用于请求游戏服务端数据
     * @param array $erl_conf       用于获取配置文件的数组，格式必须为array('game' => '', 'platform' => '', 'zone_id' => 1, 'status' => 'test|audit|stable')，status 为空则默认为 stable
     * @param array $params         array('m'=>'web_charge', args=>$param)
     * @param bool $isResp          默认为 TRUE, TRUE表示同步等待返回； FALSE 则表示异步
     * @return array $result        array('code' => int, 'msg' => str), code=0表示成功请求，其它为请求处理失败代码
     */
    public static function fetchSocket($erl_conf, $params, $isResp) {
        self::_genIpPort($erl_conf);
        
        $fun = $params ['m'];
        $args = $params ['args'];
        
        $Fs = array (); // 模式数组
        $Vs = array (); // 值数组  
        foreach ( $args as $a ) {
            array_push ( $Fs, $a [0] );
            array_push ( $Vs, $a [1] );
        }
         
        $format = '[[';
        foreach ($Fs as $f) {
            $format .= "{$f},";
        }
        $format = substr($format, 0, -1).']]';
        //接口成功请求返回1+长度为3的数组，接口出错返回0+长度为2的数组
        $socketMsg = SocketHelper::erl(self::$cache_ip, self::$cache_port, 'web_api', $fun, $format, array($Vs), $isRsp);
        $c = count($socketMsg);
        $result = array();
        if($socketMsg[0] == 0 && $c == 2) { //此处为执行失败
            $result = array('code' => -1, 'msg' => $socketMsg[1]);
        } elseif ($socketMsg[0] == 1 && $c == 3) {//$socketMsg[0] 1表示查询成功
            $result = array('code' => 0, 'msg' => $socketMsg[2]);
        } elseif ($socketMsg[0] == 2 && $c == 2) {
            $result = array('code' => 2, 'msg' => 'connect failure!');
        }
         
        return $result;
    }
    
    /**
     * 根据 $prefix 获取对应的参数配置文件
     * @param string $prefix    渠道/分组 定义，与 config/platform/ 下面 php 文件的前半部分相同
     * @param string $app_id    SDK服务器推送过来的，对应的 game_id / app_id
     * @return array $plat_conf
     */
    public static function getPlatConf($prefix, $app_id = NULL)
    {
        $plat_conf = require_once CONFIG_PATH.'/platform/'.$prefix.'.conf.php';
        if (empty($app_id))
            return $plat_conf;
        return $plat_conf[$app_id];
    }
    
    /**
     *
     * @param string $host
     * @param int $port
     * @param string $m 模块名，统一为 web_api
     * @param string $f 要调用的方法，即 web_api 里面的方法
     * @param string $format [i,b,b]；i为int；b为string，与 $args 数组里面的元素类型一一对应
     * @param array $args 要传递给 $f 调用的参数
     * @param array $isResp true表示等待服务端返回，false为不等待返回
     * @return array 返回结果数组
     */
    private static function erl($host, $port, $m = 'web_api', $f, $format = '', $args = array(), $isResp = true) {
//         echo $host, ' - ', $port;die;
        
        // TODO:以后改成读配置或者数据库
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (!$socket) {
            return 'create_conn_failed';
        }
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec"=>2, "usec"=>0));
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec"=>2, "usec"=>0));

        $conn = socket_connect($socket, $host, $port);
        // 	    var_dump($conn);die;
        if (!$conn) {
            return array(2, '连接出错！');
        }
        socket_write($socket, "web_conn---------------");
        $time = time();
        if (trim($format) === '') {
            $data = array($time, $format, $m, $f);
        } else {
            $data = array($time, $format, $m, $f, $args);
        }
        $data = json_encode($data);
        socket_write($socket, pack('n', strlen($data)));
        socket_write($socket, $data);
         
        if ($isResp) {
            $recvData = '';
            while ($r = socket_recv($socket, $bufs, 1024, 0)) {
                $recvData .= $bufs;
            }
//             sleep(7);
//             print_r(json_decode($recvData));
            //     	    var_dump(json_decode($recvData));die;
            socket_close($socket);
            return json_decode($recvData);
        }
    }
    
    
    
}

