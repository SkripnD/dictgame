<?php

/**
 * Create a new administrator account
 */
class CreateAdminCommand extends CConsoleCommand
{
    /**
     * Provides the command description.
     * @return string the command description.
     */
    public function getHelp()
    {
        return <<<EOD
USAGE
yiic createadmin <login> <password>

DESCRIPTION
Console command for create a new administrator account

EOD;
    }

    public function run($args)
    {
        if (count($args) != 2) {
            echo "Please read help: yiic help createadmin\n";
            Yii::app()->end();
        }
        
        $login = $args[0];
        $password = $args[1];
        
        $user = new Users();
        $user->login    = $login;
        $user->password = $password;
        $user->access   = Users::ACCESS_ADMIN;
        $user->active   = 1;
        
        if ($user->save()) {
            echo "Admin successfully created\n";
            echo "Login: {$login}\n";
            echo "Password: {$password}\n";
        } else {
            echo "Failed\n";
        }
    }
}
