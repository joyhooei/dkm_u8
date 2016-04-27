<?php
/**
 * 应用总入口
 * @author wqm  QQ : 2508943091
 * @Description: 
 * Date : 2016-4-4 下午5:52:35
 */
class Application extends HyControler
{
    
    public function start()
    {
        
        if (!isset($_GET['m']))
            exit('非法调用，需要有 m 模块请求参数');
        $this->runModule();
        
        // 这里，预留作为接口请求处理的加密校验
//         if (strpos($_GET['m'], 'MD5')) {
//             $isAuth = new HyAuthor();
//             if ($isAuth->isAuth())
//                 $this->runModule();
//             exit;
//         }
//         // 这里是解密 rsa 数据，因为客户端发送过来的数据是经过 rsa 加密跟base64处理过的
//         if (strpos($_GET['m'], 'Rsa'))
//         {
//             loadPlugins('HyRsa');
//             $rsa = new HyRsa();
            
//             $this->runModule();
//             exit;
//         }
//         exit ('非法调用');
    }
}

