<?php

class LoginController extends FrontController
{
    private $providers = [];
    
    public function filters()
    {
        return ['accessControl'];
    }

    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => ['socials', 'process'],
                'users'   => ['?'],
            ],
            ['deny']
        ];
    }
    
    public function init()
    {
        $this->providers = require_once Yii::app()->basePath.'/config/providers.php';
        $this->providers['base_url'] = absUrl('/login/process');
    }
    
    public function actionSocials($provider = 'twitter')
    {
        // validation provider
        if (!isset($this->providers['providers'][$provider]) ||
            !$this->providers['providers'][$provider]['enabled']) {
            return $this->accessDenied();
        }
        
        $hybridauth = new Hybrid_Auth($this->providers);
            
        if (Hybrid_Auth::isConnectedWith($provider)) {
            $adapter = Hybrid_Auth::getAdapter($provider);
        } else {
            $adapter = $hybridauth->authenticate(
                $provider,
                ['hauth_return_to' => '/login/socials?provider='.$provider]
            );
        }
        
        // try get profile
        $data = $this->tryMe(function () use ($adapter) {
            return [
                'profile' => $adapter->getUserProfile(),
                'tokens'  => $adapter->getAccessToken()
            ];
        }, function () {
            return false;
        });

        $isProfile = isset($data['profile']) && is_object($data['profile']);
        $isTokens  = isset($data['tokens'])  && is_array($data['tokens']);
        
        $profile = $data['profile'];
        $tokens  = $data['tokens'];

        // check identifier
        if ($isProfile && $isTokens && $profile->identifier > 0) {
            if ($social = Socials::model()->findByKey($provider)) {

                if (!empty($profile->emailVerified)) {
                    $email = $profile->emailVerified;
                } else {
                    $email = getPost('email', null);
                }
                
                $user = $this->socialAuth($profile, $tokens, $social, $email);
                
                if ($user) {
                    // if all ok, authorize
                    $user->forceAuthorize();
                    return $this->redirect('/');
                }
                
                return $this->render('socials', [
                    'email'  => $email,
                    'social' => $social
                ]);
            }
        }
            
        $this->alert(
            'Извините, авторизация через социальные сети
             временно недоступна или превышен лимит попыток, 
             попробуйте позже'
        );
    }
    
    public function actionProcess()
    {
        Hybrid_Endpoint::process();
    }
    
    private function socialAuth($profile, $tokens, $social, $email)
    {
        if ($user = Users::model()->checkSocialIdentifier($social->id, $profile->identifier)) {
            // if exist identifier
            return $user;
        }
        
        if (empty($email)) {
            return false;
        }

        return $this->transaction(function () use ($profile, $tokens, $social, $email) {
            $user = Users::model()->findByEmail($email);

            if (!$user) {
                if (!$user = $this->prepareUser($email, $profile)) {
                    return false;
                }
            } elseif ($user && !$profile->emailVerified) {
                Yii::app()->user->setFlash('errors', 'Такой Email уже занят');
                return false;
            }
            
            // bind to social profile
            if ($user->bindSocial(
                $social->id,
                $profile->identifier,
                $profile->profileURL,
                $tokens['access_token'],
                $tokens['access_token_secret']
            )) {
                return $user;
            }
            
            Yii::app()->user->setFlash(
                'errors',
                'Извините, авторизация через «'.$social->title.'» 
                 временно недоступна или превышен лимит попыток, 
                 попробуйте позже'
            );
            return false;
            
        }, function () use ($social) {
            Yii::app()->user->setFlash(
                'errors',
                'Извините, авторизация через «'.$social->title.'» 
                 временно недоступна или превышен лимит попыток, 
                 попробуйте позже'
            );
            return false;
        });
    }
    
    private function prepareUser($email, $profile)
    {
        $user = new Users();
        $user->scenario = 'social';
        $user->email    = $email;
        $user->active   = 1;
        $user->profile            = new UsersProfiles();
        $user->profile->firstName = $profile->firstName;
        $user->profile->lastName  = $profile->lastName;
        $user->profile->photo     = $profile->photoURL;
        $user->profile->birthDay  = $profile->birthYear.'-'.$profile->birthMonth.'-'.$profile->birthDay;
        
        if (!$user->save()) {
            Yii::app()->user->setFlash('errors', $user->getErrors());
            return false;
        }
        
        return $user;
    }
}
