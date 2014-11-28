<?php

use \WebGuy;

class TagsCest
{
    public function tryToTestAdminTags(WebGuy $I, $scenario) 
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
        $I->amOnPage('/admin/tags');
        $I->see('Теги');
    }
    
    private function actionEdit($I)
    {
        $I->amOnPage('/admin/tags/edit');
        $I->see('Сохранить');
        
        $I->sendAjaxPostRequest('/admin/tags/edit', [
            'csrf-token' => $I->getCsrfTOken(),
            'Tags[title]' => 'test'
        ]);
        
        $I->seeResponseCodeIs(200);
        $I->see('redirect');
        
        $I->amOnPage('/admin/tags');
        $I->see('test');
    }
}
