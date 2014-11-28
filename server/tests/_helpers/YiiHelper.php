<?php
 
namespace Codeception\Module;

class YiiHelper extends \Codeception\Module\Db 
{
    protected $requiredFields = []; 
 
    public function _initialize()
    {    
        defined('YII_DEBUG') or define('YII_DEBUG', true);
        
        $configs = require __DIR__ . '/../../protected/config/local/config-test-db.php';
        
        $this->config['dsn']      = $configs['connectionString'];
        $this->config['user']     = $configs['username'];
        $this->config['password'] = $configs['password'];
        
        parent::_initialize();
    }
}