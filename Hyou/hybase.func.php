<?php


/**
 * 根据类名自动加载类 
 * @param string $class
 * @return boolean
 */
function HyLoader($class) {
    $class = ucfirst($class);
    // 判断类是否已经加载过了，加载过了就直接返回，不用再次 require 了
    if (class_exists($class, FALSE) || interface_exists($class, FALSE))
        return TRUE;
    
    $file = str_replace('_', Hy_DS, $class);
    require_once $file.'.php';
    return TRUE;    
}

/**
 * 加载插件类
 * @param string $plugin    不包含 .php 文件后缀
 * @return boolean
 */
function loadPlugins($plugin) {
    require_once PLUGINS_PATH.'/'.$plugin.'.php';
    return TRUE;
}

