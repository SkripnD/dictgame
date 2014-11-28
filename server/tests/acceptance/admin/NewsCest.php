<?php

use \WebGuy;

class NewsCest
{
    public function tryToTestAdminNews(WebGuy $I, $scenario) 
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
        $I->amOnPage('/admin/news');
        $I->see('Новости');
    }
    
    private function actionEdit($I)
    {
        $I->amOnPage('/admin/news/edit');
        $I->see('Сохранить');
        
        $I->sendAjaxPostRequest('/admin/news/edit', [
            'csrf-token' => $I->getCsrfTOken(),
            'News[title]' => 'test',
            'News[text]' => 'test',
            'News[sectionsArray][]' => 1,
            'News[datePub]' => '01/01/2001',
            'News[preview]' => '/public/uploads/files/1/2/test.png'
        ]);
        
        $I->seeResponseCodeIs(200);
        $I->see('redirect');
        
        $I->amOnPage('/admin/news');
        $I->see('test');
    }
}
