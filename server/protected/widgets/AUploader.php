<?php

/**
 * Widget to upload files
   
   Usage for a single file:
   $this->widget('AUploader', [
         'url'         => url('photoUpload'),
         'model'       => $model,
         'attribute'   => 'photo',
         'rules'       => $model->getRulesForFiles(Files::OWNER_TYPE_NEWS_PHOTO),
         'watermark'   => true,
   ]);
   
   Usage for a single file without model
   $this->widget('AUploader', [
         'url'         => url('photoUpload'),
         'name'        => 'photo',
         'value'       => …,
         'labelText'   => 'Photo',
         'rules'       => $model->getRulesForFiles(Files::OWNER_TYPE_NEWS_PHOTO),
         'watermark'   => true,
   ]);
   
   Usage with gallery:
   $this->widget('AUploader', [
         'url'         => url('galleryUpload'),
         'files'       => $model->getFiles(Files::OWNER_TYPE_NEWS_GALLERY),
         'rules'       => $model->getRulesForFiles(Files::OWNER_TYPE_NEWS_GALLERY),
         'inputTitle'  => true,
         'watermark'   => true
   ]);
 */
class AUploader extends CWidget
{
    /**
     * @var string
     */
    public $url;
    /**
     * @var Model 
     */
    public $model = null;
    /**
     * @var string
     */
    public $attribute = null;
    /**
     * @var array
     */
    public $files = null;
    /**
     * @var string trigger name, called after upload
     */
    public $trigger = null;
    /**
     * @var bool
     */
    public $multiple = true;
    /**
     * @var bool autoupload
     */
    public $auto = true;
    /**
     * @var string
     */
    public $description = null;
    /**
     * @var bool show input title
     */
    public $inputTitle = false;
    /**
     * @var bool show attrubute label
     */
    public $label = true;
    /**
     * @var string change label text
     */
    public $labelText = '';
    /**
     * @var bool set watermark
     */
    public $watermark = false;
    /**
     * @var array rules for files
     */
    public $rules = [];
    /**
     * @var string register script
     */
    public $script = null;
    /**
     * @var string unique id
     */
    public $uid;
    /**
     * @var string the input id, auto set
     */
    public $id = null;
    /**
     * @var string the input name, must be set if model is not set
     */
    public $name = null;
    /**
     * @var string the input value, must be set if model is not set
     */
    public $value = null;
    /**
     * @var bool show remove button
     */
    public $showRemove = true;
    /**
     * @var bool preview
     */
    public $preview = false;
    /**
     * @var bool crop image
     */
    public $crop = false;
    /**
     * @var string preview tag
     */
    public $previewTag = '<img src="{file}" />';
    /**
     * @var string template view
     */
    public $template = 'auploader/default';
    /**
     * @var string modal view
     */
    public $modal = 'auploader/modal';
    
    public function init()
    {
        $this->uid = uniqid();
        
        Yii::app()->clientScript->registerPackage('fileapi');
        
        if ($this->crop) {
            Yii::app()->clientScript->registerPackage('jscrop');
        }
        
        $path = Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.widgets.assets'));

        Yii::app()->clientScript->registerScriptFile($path.'/auploader/script.js');
        Yii::app()->clientScript->registerCssFile($path.'/auploader/style.css');
        
        if ($this->script !== null) {
            Yii::app()->clientScript->registerScript(
                'auploader-script-'.$this->uid,
                $this->script
            );
        }
        
        $this->previewTag = $this->preview ? $this->previewTag : '';
        $this->setFieldAttributes();
        $this->setDefaultTrigger();
        $this->setDescription();
        
        $this->render($this->template);
        $this->render($this->modal);
    }

    public function run()
    {

    }
    
    private function setFieldAttributes()
    {
        if ($this->model !== null && $this->attribute !== null) {
            $this->id    = CHtml::activeId($this->model, $this->attribute);
            $this->name  = CHtml::activeName($this->model, $this->attribute);
            $this->value = $this->model->{$this->attribute};
        
            if ($this->label && empty($this->labelText)) {
                $this->labelText = $this->model->getAttributeLabel($this->attribute);
            }
        } else {
            $this->id = $this->name;
        }
    }
    
    private function setDefaultTrigger()
    {
        if ($this->files === null && $this->trigger === null) {
            $this->trigger = 'auploader-trigger-'.$this->uid;
        }
    }
    
    private function setDescription()
    {
        $rules = Files::rulesToString($this->rules);
        
        if ($this->description === null) {
            $this->description = '';
        }
        
        if (!empty($rules) && !empty($this->description)) {
            $this->description .= ' / ';
        }
        
        $this->description .= Files::rulesToString($this->rules);
        
        if ($this->watermark && !empty($this->description)) {
            $this->description .= '<br />';
        }
    }
}
