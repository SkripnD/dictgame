<?php

/**
 * @author ElisDN <mail@elisdn.ru>
 * @author rkit <rkit.ru@gmail.com> rework
 * @link http://www.elisdn.ru
 */
class Languages
{
    public static function enabled()
    {
        return count(Yii::app()->params['languages']) > 1;
    }
 
    public static function processLangInUrl($url)
    {
        if (self::enabled()) {
            $languages = array_keys(Yii::app()->params['languages']);
            
            $domains  = explode('/', ltrim($url, '/'));
            $language = $domains[0];
                        
            if ($isLangExists = in_array($language, $languages)) {
                // remove language from url
                array_shift($domains);
            } else {
                // trying to determine automatically
                $language = self::autoDetect();
            }
            
            // if the selected language exists
            if (in_array($language, $languages)) {
                // set
                Yii::app()->setLanguage($language);
                // if changed manually
                if (Yii::app()->request->getQuery('language')) {
                    // save language
                    Yii::app()->user->setState('language', $language);
                    // in cookie
                    $cookie = new CHttpCookie('language', $language);
                    $cookie->expire = time() + (60 * 60 * 24 * 365);
                    
                    Yii::app()->request->cookies['language'] = $cookie;
                }
                
                $url = '/' . implode('/', $domains);
            }
        }
 
        return $url;
    }
 
    public static function addLangToUrl($url)
    {
        if (self::enabled()) {
            $domains = explode('/', ltrim($url, '/'));
            $isHasLang = in_array($domains[0], array_keys(Yii::app()->params['languages']));
            $isDefaultLang = Yii::app()->getLanguage() == Yii::app()->params['defaultLanguage'];
 
            if ($isHasLang && $isDefaultLang) {
                array_shift($domains);
            }
 
            if (!$isHasLang && !$isDefaultLang) {
                array_unshift($domains, Yii::app()->getLanguage());
            }
 
            $url = '/' . implode('/', $domains);
        }
 
        return $url;
    }
    
    /**
     * Auto detect language
     * @return string|false
     */
    public static function autoDetect()
    {
        if (isset($_GET['language'])) {
            $language = $_GET['language'];
        } elseif (Yii::app()->user->hasState('language')) {
            $language = Yii::app()->user->getState('language');
        } elseif (isset(Yii::app()->request->cookies['language'])) {
            $language = Yii::app()->request->cookies['language']->value;
        } else {
            $language = substr(Yii::app()->getRequest()->getPreferredLanguage(), 0, 2);
        }

        return $language;
    }
}
