<?php 

/**
 * Escapes the given string using CHtml::encode().
 * @param $text
 * @return string
 */
function e($text)
{
    return CHtml::encode($text);
}

/**
 * Translates the given string using Yii::t().
 * @param $category
 * @param $message
 * @param array $params
 * @param string $source
 * @param string $language
 * @return string
 */
function t($category, $message, $params = [], $source = null, $language = null)
{
    return Yii::t($category, $message, $params, $source, $language);
}

/**
 * Creates a relative URL using CController::createUrl().
 * @param $route
 * @param array $params
 * @param string $ampersand
 * @return mixed
 */
function url($route, $params = [], $ampersand = '&')
{
    return Yii::app()->getController()->createUrl($route, $params, $ampersand);
}

/**
 * Creates a absolute URL using CController::createAbsoluteUrl().
 * @param $route
 * @param array $params
 * @param string $schema
 * @param string $ampersand
 * @return mixed
 */
function absUrl($route, $params = [], $schema = '', $ampersand = '&')
{
    return Yii::app()->getController()->createAbsoluteUrl($route, $params, $schema, $ampersand);
}

/**
 * Dumps the given variable using CVarDumper::dumpAsString().
 * @param mixed $var
 * @param int $depth
 * @param bool $highlight
 */
function dump($var, $depth = 10, $highlight = true)
{
    echo CVarDumper::dump($var, $depth, $highlight);
}

/**
 * Get pages params
 * @param $param
 * @param array $exclude
 * @return array|string
 */
function pagesParams($param = null, $exclude = [])
{
    $params = Yii::app()->getController()->pages->params;
    
    if ($exclude) {
        foreach ($exclude as $p) {
            unset($params[$p]);
        }
    }
    
    return $param === null ? $params : $params[$param];
}

/**
 * Get the value (with a check for the existence of)
 * @param mixed $data
 * @param mixed $param
 * @param $returnDefault
 * @return string|bool
 */
function param($data, $param, $returnDefault = false)
{
    if (is_object($data)) {
        if (isset($data[$param])) {
            return $data[$param];
        }
            
        return isset($data) && isset($data->{$param}) ? $data->{$param} : $returnDefault;
        
    } else {
        return isset($data) && isset($data[$param]) ? $data[$param] : $returnDefault;
    }
}

/**
 * Validate
 * @param string $validator name
 * @param string $value
 * @return mixed
 */
function validate($validator, $value)
{
    $validator = new $validator();
    return $validator->validateValue($value);
}

/**
 * Return document root
 * @return string
 */
function webroot()
{
    return $_SERVER['DOCUMENT_ROOT'];
}

/**
 * Return remote addr
 * @return string
 */
function ip()
{
    return $_SERVER['REMOTE_ADDR'];
}

/**
 * Return host info
 * @return string
 */
function hostInfo()
{
    return Yii::app()->request->hostInfo;
}

/**
 * Return language
 * @return string
 */
function language()
{
    return Yii::app()->language;
}

/**
 * Return current user
 * @return CActiveRecord
 */
function user()
{
    return Yii::app()->user;
}

/**
 * Returns the named POST parameter value.
 * If the POST parameter does not exist, the second parameter to this method will be returned.
 * @param string $name the POST parameter name
 * @param mixed $defaultValue the default parameter value if the POST parameter does not exist.
 * @return mixed the POST parameter value
 * @see getParam
 * @see getQuery
 */
function getPost($name, $defaultValue = null)
{
    return Yii::app()->request->getPost($name, $defaultValue);
}

/**
 * Returns the named GET parameter value.
 * If the GET parameter does not exist, the second parameter to this method will be returned.
 * @param string $name the GET parameter name
 * @param mixed $defaultValue the default parameter value if the GET parameter does not exist.
 * @return mixed the GET parameter value
 * @see getPost
 * @see getParam
 */
function getQuery($name, $defaultValue = null)
{
    return Yii::app()->request->getQuery($name, $defaultValue);
}

/**
 * Return settings
 * @return ApplicationSetting
 */
function settings($type = 'main')
{
    return Yii::app()->settings->{$type};
}
