<?php


class Pay_Platform_JunhaiHandle extends PayAdmin
{
	
    public function __construct()
    {
        parent::__construct(self::ENCI_TYPE);
        $conf = PaySysHelper::getPlatConf('Junhai');
        
        print_r($conf);
    }
    
    
    
    public function genOrder()
    {
        
        
        
    }
    
    
}
