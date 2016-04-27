<?php

/**
 * 框架初始化文件
 * 1、先定义好框架的根目录、类库目录、插件目录
 * 2、定义应用根目录、模块目录
 * 3、设定包含路径
 */

if (!defined('ROOT')) {
    exit('先定义项目application根目录');
}

define('HyFrame_ver', 'v1.0');
define('Hy_DS', DIRECTORY_SEPARATOR);
define('HFrame_PATH', realpath(dirname(__FILE__)));

define('LIB_PATH', HFrame_PATH.'/lib/');
define('PLUGINS_PATH', HFrame_PATH.'/plugins/');

define('APP_PATH', ROOT.'/application/');
define('MODULE_PATH', APP_PATH.'/module/');
define('CONFIG_PATH', APP_PATH.'/config');

// 设置项目启动包含路径，以供 HyLoader 使用
set_include_path(PATH_SEPARATOR.LIB_PATH.
    PATH_SEPARATOR.PLUGINS_PATH.
    PATH_SEPARATOR.MODULE_PATH.
    PATH_SEPARATOR.get_include_path());

require_once HFrame_PATH.'/hybase.func.php';    // 加载基类
require_once LIB_PATH.'/HyUtil.php';            // 加载辅助类
require_once CONFIG_PATH.'/u8.conf.php';           // 加载配置文件

spl_autoload_register('HyLoader');// 注册加载类函数，不使用系统默认的 __autoload





