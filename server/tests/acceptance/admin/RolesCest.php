<?php

use \WebGuy;

class RolesCest
{
    public function tryToTestAdminRoles(WebGuy $I, $scenario) 
    {
        // authorize
        $I = new WebGuy\AdminUsersSteps($scenario);
        $I->login();
        
        // actions
        $this->actionIndex($I);
        $this->actionEdit($I);
    }
    
    private function actionIndex($I)
    {
        $I->amOnPage('/admin/roles');
        $I->see('Роли');
    }
        
    private function actionEdit($I)
    {
        $I->amOnPage('/admin/roles/edit');
        $I->see('Сохранить');
        
        $I->sendAjaxPostRequest('/admin/roles/edit', [
            'csrf-token' => $I->getCsrfTOken(),
            'AuthItem[description]' => 'test',
            'AuthItem[name]' => 'test',
        ]);
        
        $I->seeResponseCodeIs(200);
        $I->see('redirect');
        
        $I->amOnPage('/admin/roles');
        $I->see('test');
    }
}
