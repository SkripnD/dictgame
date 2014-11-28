<?php

class Url
{
    /**
     * Get route without action
     * @return string
     */
    public static function withoutAction()
    {
        if (Yii::app()->controller->module) {
            return '/'.Yii::app()->controller->module->id.'/'.Yii::app()->controller->id;
        } else {
            return '/'.Yii::app()->controller->id;
        }
    }
    
    public static function swithLanguage($language)
    {
        return '/'.Yii::app()->controller->route.'?language='.$language;
    }
    
    public static function setPrimaryDomain($url)
    {
        $host = parse_url($url)['host'];
        return str_replace($host, substr($host, strpos($host, '.') + 1), $url);
    }
    
    /**
     * Created another parse_url utf-8 compatible function.
     * @param string $url
     * @param int PHP_URL_SCHEME, PHP_URL_HOST, PHP_URL_PORT, PHP_URL_USER, PHP_URL_PASS, 
     PHP_URL_PATH, PHP_URL_QUERY or PHP_URL_FRAGMENT 
     to retrieve just a specific URL component as a string (except when PHP_URL_PORT is given, 
     in which case the return value will be an integer). 
     * @return string|array
     */
    public static function parseUrl($url, $param = -1)
    {
        $encodedUrl = preg_replace('%[^:/?#&=\.]+%usDe', 'urlencode(\'$0\')', $url);
        $components = parse_url($encodedUrl, $param);
        
        if (!$components) {
            return $url;
        }
        
        if (is_array($components)) {
            foreach ($components as &$component) {
                $component = urldecode($component);
            }
        } else {
            $components = urldecode($components);
        }
        
        return $components;
    }
}
