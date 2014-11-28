<?php

return [    
    'providers' => [
        'twitter' => [
           'enabled' => true,
           'keys' => [
               'key'    => '', 
               'secret' => ''
           ]
        ],
        'facebook' => [
           'enabled' => true,
           'keys' => [
               'id'     => '', 
               'secret' => ''
           ]
        ],
        'vkontakte' => [
           'enabled' => true,
           'wrapper' => [
               'path' => WEBROOT.'/vendor/hybridauth/hybridauth/additional-providers/hybridauth-vkontakte/Providers/Vkontakte.php', 
               'class' => 'Hybrid_Providers_Vkontakte'
           ],
           'keys' => [
               'id'     => '', 
               'secret' => ''
           ]
        ],
    ],
];
