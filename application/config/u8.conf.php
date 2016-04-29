<?php
// CONFIG 是全局参数变量

//数据库配置
$CONFIG['db'] = array(
    'host' => '127.0.0.1',
    'user' => 'root',
    'pwd' => 'test',
    'port' => 3306,
    'charset' => 'utf8',
    'pconnect' => '0',
    'type' => 'mysql',
    'db_name' => 'dkm_dmp', // 各个项目的汇总数据库名称不同
);

$CONFIG['redis'] = array(
	'host' => '127.0.0.1',
    'port' => 6379,
);



