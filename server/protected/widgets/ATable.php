<?php

/**
 * Widget to display a list
 
   Usage:
   $this->widget('ATable', [
       'model'      => 'News',
       'linktoview' => '/news/{id}',
       'data'       => $list,
       'pos'        => true,
       'fields'     => ['title', 'datePub', 'sectionsTitles', 'active'], 
       
       'searchPlaceholder' => 'Поиск',
       
       'searchParams' => [0 => 'По названию', 1 => 'По описанию'], 
       
       'statuses' => [
           'active' => [
               '0' => ['title' => 'скрыто',  'class' => 'warning'], 
               '1' => ['title' => 'активно', 'class' => 'success']
           ]
        ],
                                                                                                
       'filters' => [
           'sectionId' => [
               'data'  => CHtml::listData($sections, 'id', 'title'),
               'empty' => 'Все разделы'
           ]
       ],
             
       'filtersButton' => [
           'active' => [
               'Активные' => ['active' => 1], 
               'Скрытые'  => ['active' => 0]
            ]
       ]]);
 */
class ATable extends CWidget
{
    /**
     * @var string model name
     */
    public $model;
    /**
     * @var array columns from model
     */
    public $fields = [];
    /**
     * @var array 
     */
    public $statuses = [];
    /**
     * @var string record review
     */
    public $linktoview = null;
    /**
     * @var string primary key name
     */
    public $primary = 'id';
    /**
     * @var array
     */
    public $filters = [];
    /**
     * @var array
     */
    public $filtersButton = [];
    /**
     * @var array
     */
    public $searchParams = [];
    /**
     * @var string
     */
    public $searchPlaceholder = 'Поиск';
    /**
     * @var string table header
     */
    public $header = null;
    /**
     * @var bool
     */
    public $showButtonAdd = true;
    /**
     * @var bool
     */
    public $showSearch = true;
    /**
     * @var bool enable manual sort
     */
    public $pos = false;
    /**
     * @var bool enable search autocomplete
     */
    public $searchAutocomplete = false;
    /**
     * @var string source url for autocomplete
     */
    public $searchAutocompleteUrl = null;
    /**
     * @var array exclude from sorting
     */
    public $sortExclude = [];
    /**
     * @var array
     */
    public $operations = ['delete', 'setactive', 'setinvisible'];
    /**
     * @var string
     */
    public $operationsAction = 'operations';
    /**
     * @var array data
     */
    public $data = [];

    public $attributes;
    public $filtersNames = [];
    
    public function init()
    {
        $path = Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.widgets.assets'));

        Yii::app()->clientScript->registerPackage('jquery');
        Yii::app()->clientScript->registerScriptFile($path.'/atable/script.js');
        Yii::app()->clientScript->registerCssFile($path.'/atable/style.css');
            
        $this->model = new $this->model();
        
        if ($this->searchAutocomplete && $this->searchAutocompleteUrl === null) {
            $this->searchAutocompleteUrl = url('');
        }
        
        $this->header = $this->header ? $this->header : $this->getController()->pageTitle;
        
        $this->prepareFields();
        $this->prepareFiltersNames();
    }
 
    public function run()
    {
        $model = new $this->model;
        
        echo '<div class="widget-atable">';
        
        $this->render('atable/controls');
        $this->render('atable/thead');
        
        foreach ($this->data as $row) {
            $this->render('atable/row', ['row' => $row]);
        }
        
        $this->render('atable/tfoot');
        $this->render('atable/operations');
        
        echo '</div>';
    }
    
    private function prepareFiltersNames()
    {
        foreach ($this->filters as $filterName => $v) {
            $this->filtersNames[] = $filterName;
        }
    }
    
    private function prepareFields()
    {
        $this->attributes = $this->model->attributeLabels();
        
        $fields = [];
        foreach ($this->fields as $field => $param) {
            $fieldSource = is_object($param) ? $field : $param;
            $field = str_replace('t.', '', $fieldSource);

            if (!isset($this->attributes[$field])) {
                throw new Exception('Please set a label for the field "'.$field.'"');
            }
            
            $fields[$field] = [
                'label' => $this->attributes[$field],
                'closure' => $param,
                'fieldSource' => $fieldSource
            ];
        }
        
        $this->fields = $fields;
    }
    
    public function sort($data)
    {
        $this->render('atable/sort', $data);
    }
    
    public function sortForce($data)
    {
        $this->render('atable/sort-force', $data);
    }

    public static function getAttribute($obj, $attribute, $value = null)
    {
        $attributes = explode('.', $attribute);

        foreach ($attributes as $attr) {

            if ($obj === null) {
                throw new Exception("Null object reference '$attribute'");
            }

            if (is_a($value['closure'], 'Closure')) {
                return null;
            }

            $getter = 'get' . $attr;

            if (property_exists($obj, $attr)) {
                $obj = $obj->$attr;
            } elseif (is_a($obj, 'CActiveRecord') && ($obj->hasAttribute($attr) || $obj->hasRelated($attr))) {
                $obj = $obj->$attr;
            } elseif (method_exists($obj, $getter)) {
                $obj = $obj->$getter();
            } else {
                throw new Exception("Attribute '$attribute' not found");
            }
        }

        return $obj;
    }
}
