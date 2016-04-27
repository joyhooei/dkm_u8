<?php

class Auth_SDKInit
{
    
    public function __construct()
    {
        
        
        
        $db = DB::getInstance();
        $game_id = Jec::getInt('game_id');
        $row = $db->getRow("select * from dkm_game where game_id='{$game_id}'");
        empty($row) ? exit(json_encode(array('state' => NO_PARTNER_CONF, 'data' => 'empty conf'))) : exit(json_encode(array('state' => SUCCESS, 'data' => $row)));
    }
    
}

