<?php

namespace WebGuy;

class AdminUsersSteps extends \WebGuy
{
    function login()
    {
        $I = $this;
        
        $I->amOnPage('/admin');
        $I->see('Панель управления');
        $I->dontSee('Выйти');

        $I->amOnPage(\AdminLoginPage::$URL);
        
        $I->see('Панель управления');
        $I->fillField(\AdminLoginPage::$loginField, 'test');
        $I->fillField(\AdminLoginPage::$passwordField, 'test');
        $I->click('Войти');
        $I->see('Неправильный пароль');
        
        $I->fillField(\AdminLoginPage::$loginField, \AdminLoginPage::$login);
        $I->fillField(\AdminLoginPage::$passwordField, \AdminLoginPage::$password);
        $I->click('Войти');
        $I->see('Выйти');
    }
    
    function logout()
    {
        $I = $this;
        
        $I->click('Выйти');
        $I->amOnPage('/admin');
        $I->see('Панель управления');
    }
    
    function sendAjaxRequest($method, $uri, $params)
    {
        return parent::sendAjaxRequest($method, '/index-test.php'.$uri, $params);
    }
    
    function sendAjaxGetRequest($uri, $params)
    {
        return parent::sendAjaxGetRequest('/index-test.php'.$uri, $params);
    }
    
    function sendAjaxPostRequest($uri, $params)
    {
        return parent::sendAjaxPostRequest('/index-test.php'.$uri, $params);
    }
    
    function getCsrfTOken()
    {
        return $this->grabValueFrom('//input[@name="csrf-token"]');   
    }
}
