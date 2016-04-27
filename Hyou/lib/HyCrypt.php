<?php

class HyCrypt
{
    
    /**
     * 对 $var 进行AES加密
     * @param string $data
     * @return string $return   经过 base64_encode 的数据
     */
    public static function esEncrypt($data)
    {
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_256,'',MCRYPT_MODE_CBC,'');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td),MCRYPT_RAND);
        mcrypt_generic_init($td, AES_SECRET_KEY,$iv);
        $encrypted = mcrypt_generic($td,$data);
        mcrypt_generic_deinit($td);
         
        return base64_encode($iv . $encrypted);
    }
    
    
    /**
     * 对 $var 进行解密
     * @param string $var   经过 base64_encode 的数据
     * @return string $return
     */
    public static function aesDecrypt($data)
    {
        $data = base64_decode($data);

        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_256,'',MCRYPT_MODE_CBC,'');
        $iv = mb_substr($data,0,32,'utf-8');
        mcrypt_generic_init($td, AES_SECRET_KEY,$iv);
        $data = mb_substr($data,32,mb_strlen($data,'utf-8'),'utf-8');
        $data = mdecrypt_generic($td,$data);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
         
        return trim($data);
    }
    
}

