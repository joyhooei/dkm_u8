<?php

ini_set('date.timezone', 'Asia/Shanghai');
define('ROOT', realpath(dirname(__FILE__).'/../')); // 设置站点项目根目录
define('TIME',         $_SERVER['REQUEST_TIME'] ? $_SERVER['REQUEST_TIME'] : time() );

define('SUCCESS', 1);
define('EMPTY_GID', 2);
define('CLASS_NO_EXISTS', 3);
define('NO_PARTNER_CONF', 4);
define('INVOKE_PAR_CLASS', 5);
define('TOKEN_FAILE', 6);
define('ENCRYPT_ERROR', 7);

define('DKM_CREATE_USER_URL', 'http://118.123.216.86:89/userinfo/createUser/index.html');   // 多可梦玩家注册地址
define('DKM_LOGIN_USER_URL', 'http://118.123.216.86:89/userinfo/login/index.html');         // 多可梦玩家登陆地址
define('DKM_SECRET_KEY', 'e1090953d137be0be1a6df7139831ceb');           // 多可梦接口加密校验key

define('AES_SECRET_KEY', '6XftfdB7K4EQ5G3bj6xJzZbM6rRaDkHK');
define('AES_IV_KEY', '5efd3f6060e70330');

define('DKM_DEFAULT_PWD', md5('111'));      // 多可梦平台账号初始密码
define('SESSION_TTL', 3600);          // 会话保存时间, 秒
define('DKM_NAME_SUFFIX', 'a');

require ROOT.'/Hyou/HYinit.php';
