<?php

use \WebGuy;

class AccessCest
{
    public function tryToTestAdminAccess(WebGuy $I, $scenario) 
    {        
        // authorize
        $I = new WebGuy\AdminUsersSteps($scenario);
        $I->login();
        
        // exit
        $I->logout();
    }
}
