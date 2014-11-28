<?php 

class Page
{
    /**
     * Returns page number string
     * @param string $param
     * @return string
     */
    public static function pageString($param = 'page')
    {
        $page = (int)Yii::app()->request->getQuery($param, 1);
        return $page > 1 ? ' / Страница ' . $page : '';
    }
    
    /**
     * Register canonical tag
     * @param bool $showAction
     * @param array $params
     * @return void
     */
    public static function canonical($showAction = true, $params = [])
    {
        $url = '';
        
        if (Yii::app()->controller->module) {
            $url .= '/'.Yii::app()->controller->module->id;
        }
        
        $url .= '/'.Yii::app()->controller->id;
        
        if ($showAction) {
            $url .= '/'.Yii::app()->controller->action->id;
        }
        
        foreach ($params as $name => $value) {
            $url .= ($name ? '/'.$name : '').'/'.$value;
        }
        
        Yii::app()->clientScript->registerLinkTag(
            'canonical',
            null,
            Yii::app()->controller->createAbsoluteUrl($url)
        );
    }
    
    /**
     * Register meta tag
     * @param string $tag
     * @param string $value
     * @return void
     */
    public static function metaTag($tag, $value)
    {
        Yii::app()->clientScript->registerMetaTag($value, null, null, ['property' => $tag]);
    }
}
