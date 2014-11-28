<?php

class AdminLoginPage
{
    public static $login    = 'editor';
    public static $password = 'fghfgh';
    
    public static $loginField    = 'Users[login]';
    public static $passwordField = 'Users[password]';
    
    // include url of current page
    public static $URL = '/admin/users/login';

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: EditPage::route('/123-post');
     */
     public static function route($param)
     {
        return static::$URL.$param;
     }

    /**
     * @var WebGuy;
     */
    protected $webGuy;

    public function __construct(WebGuy $I)
    {
        $this->webGuy = $I;
    }

    /**
     * @return AdminLoginPage
     */
    public static function of(WebGuy $I)
    {
        return new static($I);
    }
}
