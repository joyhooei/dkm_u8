<?php
/**
 * 框架基础类，也就是路由控制类，在这里面启动模块
 * @author wqm  QQ : 2508943091
 * @Description: 
 * Date : 2016-4-4 下午4:32:58
 */
class HyControler
{
    private static $module_key = 'm';
    
    public static function setModuleName($name) {
        $_GET[self::$module_key] = $name;
    }
    
    public static function getModuleName() {
        return HyUtil::getKeyWord(self::$module_key, 'GET');
    }
    
    /**
     * 判断是否是一个合法的模块名称
     * @param string $name
     */
    public function isValidModule($name) {
        return (bool)preg_match('/^[A-Z]\w+(_[A-Z]\w+)*$/', $name);
    }
    
    public function runModule() {
        $module = self::getModuleName();
        if (!$this->isValidModule($module))
            exit ('非法的模块:'.$module);
        $file = str_replace('_', Hy_DS, $module);
        if (!file_exists(MODULE_PATH.'/'.$file.'.php'))
            exit('不存在对应的模块类');
        
        return new $module();
    }
    
    
    
}


