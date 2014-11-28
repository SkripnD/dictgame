<?php

$basePath = __DIR__ . '/../../protected';

/* Set aliases
-------------------------------------------------- */

Yii::setPathOfAlias('Cache', $basePath . DIRECTORY_SEPARATOR . 'extensions/cache');

$configs = array(
    'id'                => 'hUmBVKsaVjwJBh3wajQoBwD8kwpirZ',
    'name'              => 'Project',
    'basePath'          => $basePath,
    'defaultController' => 'index',
    'sourceLanguage'    => 'en',
    'language'          => 'ru',
                    
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.helpers.*',
        'application.widgets.*',
        'application.vendors.*',
        
        'ext.yii-select2.Select2',
    ),
    
    'preload' => array('log'),
    
    'components' => array(
        'user' => array(
            'allowAutoLogin' => true,
            'class'          => 'WebUser',
            'loginUrl'       => '/',
            
            'identityCookie' => array(
                'domain'   => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null,
                'httpOnly' => true
            ),
        ),
        
        'urlManager' => array(
            'class'          => 'application.components.UrlManager',
            'urlFormat'      => 'path',
            'showScriptName' => false,
            
            'rules' => array(
               '<action:\w+>' => 'index/<action>'
            )
        ),
        
        'request' => array(
            'class'                  => 'HttpRequest',
            'enableCsrfValidation'   => true,
            'enableCookieValidation' => true,
            
            'csrfTokenName' => 'csrf-token',
            
            'noCsrfValidationRoutes' => [
                '/debug/',
            ]
        ),
        
        'cacheFile' => array(
            'class' => 'system.caching.CFileCache',
        ),
        
        'cache' => array(
            'class' => 'system.caching.CFileCache',
        ),
        
        'errorHandler' => array(
            'errorAction' => YII_DEBUG ? null : 'index/error',
        ),
        
        'clientScript' => array(
            'coreScriptPosition'        => CClientScript::POS_END,
            'defaultScriptFilePosition' => CClientScript::POS_END,
            
            'packages'  => array(
                'jquery' => false,
                                                                                        
                'fileapi' => array(
                    'baseUrl' => '/public/plugins/fileapi',
                    'js'      => array('FileAPI.min.js', 'jquery.fileapi.min.js'),
                    'depends' => array('jquery'),
                ),
                
                'jscrop' => array(
                    'baseUrl' => '/public/plugins/jcrop',
                    'js'      => array('jquery.Jcrop.min.js'),
                    'css'     => array('jquery.Jcrop.min.css'),
                    'depends' => array('fileapi'),
                ),
            ),
        ),
        
        'widgetFactory' => array(
            'widgets' => array(
                'CJuiAutoComplete' => array(
                    'themeUrl' => 'public/css/admin',
                    'options' => array(
                        'minLength' => '2',
                    ),
                ),
            ),
        ),

        'redis' => array(
            'class'    => 'application.components.PRedis',
            'hostname' => 'localhost',
            'port'     => 6379,
            'database' => 0,
            'prefix'   => 'app.'
        ),
    ),
    
    /* Modules settings
    -------------------------------------------------- */
    
    'modules' => array(

    ),
    
    /* Global settings
    -------------------------------------------------- */
    
    'params' => array(        
        'languages' => array(
            'ru' => 0,
            'en' => 1,
        ),
        'defaultLanguage' => 'ru',
        'setOnlineUsers' => false,
        
        'mail' => array(
            'smtp'  => false,
            /** @see http://framework.zend.com/manual/2.2/en/modules/zend.mail.smtp.options.html **/
            'options' => array(
                'name'              => 'localhost.localdomain',
                'host'              => '127.0.0.1',
                'port'              => 587,
                'connection_class'  => 'plain',
                'connection_config' => array(
                    'username' => '',
                    'password' => '',
                    'ssl'      => 'tls',
                )
            )
        )
    )
);

require_once __DIR__ . '/local/config.php';

return $configs;

?>