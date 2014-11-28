<?php

/**
 * Widget to FileAPI
   jQuery plugin FileAPI — https://github.com/RubaXa/jquery.fileapi
   
   Usage for a single file:
   $this->widget('FileApi', [
   'selector' => '.upload',
   'template' => 'application.views.shared.upload-avatar',
   'params'   => ['param' => $value],
   'options' => [
       'url'        => '/upload',
       'multiple'   => true,
       'autoUpload' => true,
       'imageSize'  => 2000,
       'onSelect'   => 'js:function (evt, data) { 
           console.log(1); 
       }',
       'onFileComplete' => 'js:function (evt, uiEvt) { 
           console.log(2); 
       }',
   ]]);
 */
class FileApi extends CWidget
{
    /**
     * @var array {@link https://github.com/RubaXa/jquery.fileapi}
     */
    public $options = array();
    /**
     * @var string
     */
    public $selector = null;
    /**
     * @var string render template for FileApi
     */
    public $template = null;
    /**
     * @var array params for template
     */
    public $params = [];

    public function init()
    {
        if ($this->selector === null) {
            throw new Exception('Please set FileApi.selector');
        }
        
        if ($this->template !== null) {
            $this->render($this->template, $this->params);
        }
        
        $selector = "'".$this->selector."'";
        $options  = CJavaScript::encode($this->options);
        
        Yii::app()->clientScript
            ->registerPackage('fileapi')
            ->registerScript(
                'FileAPI_'.uniqid(),
                'jQuery('.$selector.').fileapi('.$options.');',
                CClientScript::POS_END
            );
    }
}
