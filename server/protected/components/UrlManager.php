<?php

/**
 * Manages the URLs of Yii Web applications
 */
class UrlManager extends CUrlManager
{
    public function createUrl($route, $params = [], $ampersand = '&')
    {
        $url = parent::createUrl($route, $params, $ampersand);
        
        if (isset(Yii::app()->controller->translate) && Yii::app()->controller->translate) {
            return Languages::addLangToUrl($url);
        }
        
        return $url;
    }
}
