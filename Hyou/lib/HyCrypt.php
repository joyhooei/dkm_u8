<?php
// 这是统一定义好的 key 值
// 使用方式
/**
define('AES_SECRET_KEY', '6XftfdB7K4EQ5G3bj6xJzZbM6rRaDkHK');
define('AES_IV_KEY', '5efd3f6060e70330');
HyCrypt::setKey(AES_SECRET_KEY);
HyCrypt::setIv(AES_IV_KEY);
$data = HyCrypt::aesEncrypt('1111');
$decrypt = HyCrypt::aesDecrypt($data);
*/

class HyCrypt
{
    private static $key = '';
    private static $iv = '';
    
    public static function setKey($key)
    {
        while (mb_strlen($key) < 16)
            $key .= '0';
        if (mb_strlen($key) > 16)
            $key = mb_substr($key, 0, 16);
        self::$key = $key;
    }
    
    public static function setIv($iv)
    {
        while (mb_strlen($iv) < 16)
            $key .= '0';
        if (mb_strlen($iv) > 16)
            $key = mb_substr($iv, 0, 16);
        self::$iv = $iv;
    }
    
    //将解密后多余的长度去掉(因为在加密的时候 补充长度满足block_size的长度)
    private static function trimEnd($text)
    {
        $len = strlen($text);
        $c = $text[$len-1];
        if(ord($c) <$len){
            for($i=$len-ord($c); $i<$len; $i++){
                if($text[$i] != $c){
                    return $text;
                }
            }
            return substr($text, 0, $len-ord($c));
        }
        return $text;
    }
    
    //将$text补足$padlen倍数的长度
    private static function pad2Length($text, $padlen)
    {
        $len = strlen($text)%$padlen;
        $res = $text;
        $span = $padlen-$len;
        for($i=0; $i<$span; $i++){
            $res .= chr($span);
        }
        return $res;
    }
    
    private static function hexToStr($hex)
    {
        $bin="";
        for($i=0; $i<strlen($hex)-1; $i+=2)
        {
            $bin.=chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $bin;
    }
    
    /**
     * 对 $var 进行AES加密
     * @param string $data
     * @return string $return   二进制转十六进制 的数据
     */
    public static function aesEncrypt($data)
    {
        $text = FALSE;
        $cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        if (mcrypt_generic_init($cipher, $this->key, $this->iv) != -1) {
            $text = mcrypt_generic($cipher, self::pad2Length($data, 16));
            mcrypt_generic_deinit($cipher);
            mcrypt_module_close($cipher);
        }
        
        return empty($text) ? FALSE : bin2hex($text);
    }
    
    
    /**
     * 对 $var 进行解密
     * @param string $var   经过 base64_encode 的数据
     * @return string | false $return
     */
    public static function aesDecrypt($data)
    {
        $text = FALSE;
        $cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        if (mcrypt_generic_init($cipher, self::$key, self::$iv) != -1) {
            $text = mdecrypt_generic($cipher, self::hexToStr($data));
            mcrypt_generic_deinit($cipher);
            mcrypt_module_close($cipher);
            $text = self::trimEnd($text);
        }
        return $text;
    }
    
}

