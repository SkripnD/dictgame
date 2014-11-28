<?php

use \WebGuy;

class SettingsCest
{
    public function tryToTestAdminSettings(WebGuy $I, $scenario) 
    {
        // authorize
        $I = new WebGuy\AdminUsersSteps($scenario);
        $I->login();

        // actions
        $this->actionChangePassword($I);
    }
    
    private function actionChangePassword($I)
    {
        $I->amOnPage('/admin/settings/changepassword');
        $I->see('Сохранить');
        
        $I->fillField(\AdminLoginPage::$loginField, \AdminLoginPage::$login);
        $I->fillField(\AdminLoginPage::$passwordField, \AdminLoginPage::$password);
        $I->fillField('Users[passwordNew]', \AdminLoginPage::$password);
        $I->click('Сохранить');
        $I->see('Пароль изменен');
    }
}
