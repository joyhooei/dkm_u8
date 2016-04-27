<?php
/**
 * RSA 加密解密类
 * @author wqm  QQ : 2508943091
 * @Description: 
 * Date : 2016-4-4 下午5:56:11
 */
class HyRsa
{
    
    const PRI_KEY = 1;  // 表示生成私钥
    const PUB_KEY = 2;  // 表示生成公钥
    
    /**
     * 将字符串转化成公密钥
     * @param string $key
     * @param int $type
     * @return resource|boolean
     */
    private static function _createRsaObj($key, $type = self::PRI_KEY) {
        switch ($type) {
        	case self::PRI_KEY:
        	   $priKey = "-----BEGIN RSA PRIVATE KEY-----\n".chunk_split($key, 64, "\n")."-----END RSA PRIVATE KEY-----";
        	   return openssl_pkey_get_private($priKey);
        	break;
        	case self::PUB_KEY:
        	    $pubKey = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($key, 64, "\n") . "-----END PUBLIC KEY-----";
        	    return openssl_pkey_get_public($pubKey);
        	default:
        		return FALSE;
        	break;
        }
        
    }
    
    
    /**
     * 密钥解密数据
     * @param string $data  要解密的base64编码后的数据
     * @param string $key   密钥数据，字符串
     * @param int $type  self::PRI_KEY 1(私钥解密) | self::PUB_KEY 2(公钥解密)
     * @return string | bool $ret
     */
    public static function decryptByStr($data, $key, $type = self::PRI_KEY) {
        if (empty($data))
            return FALSE;
        $rsaObj = self::_createRsaObj($key, $type);
        if (empty($rsaObj))
            return FALSE;
        $data = base64_decode($data);
        switch ($type) {
        	case self::PRI_KEY:    // 密钥解密
        	   $ret = openssl_private_decrypt($data, $decrypted, $rsaObj);
        	   return empty($ret) ? FALSE : $decrypted;
        	break;
        	case self::PUB_KEY:    // 公钥解密
        	    $ret = openssl_public_decrypt($data, $decrypted, $rsaObj);
        	    return empty($ret) ? FALSE : $decrypted;
        	default:
        	   return FALSE;
        	break;
        }
    }
    
    /**
     * 根据密钥字符串进行解密
     * @param string $data  加密数据
     * @param string $key   key的字符串值
     * @param int $type
     * @return boolean | mixed (解密后的数据)
     */
    public static function decryptByFile($data, $key, $type = self::PRI_KEY) {
        $rsaObj = self::_createRsaObj($key, $type);
        if (empty($rsaObj))
            return FALSE;
        $data = base64_decode($data);
        switch ($type) {
        	case self::PRI_KEY:    // 密钥解密
        	   $ret = openssl_private_decrypt($data, $decrypted, $rsaObj);
        	   return empty($ret) ? FALSE : $decrypted;
        	break;
        	case self::PUB_KEY:    // 公钥解密
        	    $ret = openssl_public_decrypt($data, $decrypted, $rsaObj);
        	    return empty($ret) ? FALSE : $decrypted;
        	default:
        	   return FALSE;
        	break;
        }
    }
    
    
}

