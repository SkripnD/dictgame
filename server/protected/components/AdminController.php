<?php

/**
 * All controller classes for AdminModule should extend from this base class
 *
 *   WARNING! ВАЖНО!
 * — Используется RBAC
 * — Для проверки доступа к текущему экшену, используется стандартный фильтр
 * — Проверка идет автоматически, проверяется разрешен ли доступ к текущему экшену по его ключу 
 * — Ключ для проверки доступа имеет вид "МодульКонтроллерЭкшен", например "AdminNewsIndex"
 * — Если необходимо разрешить доступ ко всем экшенам контроллера, указываем * вместо экшена, например "AdminNews*"
 * — Если нужно разрешить доступ к контроллеру без проверки ключа, используем свойство "allowAccess" ($this->allowAccess = true)
 * — Ключи доступа необходимо добавить в таблицу authItem, ввиде операции
 * — Все контроллеры необходимо наследовать от "AdminController"
 * — Если в контроллере идет переопределение фильтра, то необходимо делать слияние с базовым фильтром (parent::filters() + [])
 * — Для доступа в админку должна быть разрешена операция "AdminModule"
 */
class AdminController extends Controller
{
    /**
     * @var string path to js bundle
     */
    public $jsBundle = 'admin.js';
    /**
     * @var string path to css bundle
     */
    public $cssBundle = 'admin.css';
    /**
     * @var bool support language in URL
     */
    public $translate = false;
    /**
     * @var bool
     */
    public $multilingual = false;
    
    public function init()
    {
        parent::init();
     
        if (Languages::enabled()) {
            $this->prepareLanguage();
        }
    }
    
    /**
     * Set pagination
     * @param CActiveRecord|null $model
     * @param int $pageSizeMin
     * @param int $pageSizeMax
     * @return CPagination
     */
    public function pages($model = null, $pageSizeMin = 20, $pageSizeMax = 100)
    {
        $pageSize = $this->pageSize($pageSizeMin, $pageSizeMax);

        $this->pages = new CPagination();
        $this->pages->pageSize = $pageSize;
        $this->pages->params   = $_GET;
        
        if (is_object($model)) {
            $this->pages->itemCount = $model->getCount();
            $this->pages->applyLimit($model->getDbCriteria());
        }
        
        return $this->pages;
    }
    
    /**
     * Preparing language
     * @return void
     */
    public function prepareLanguage()
    {
        $this->multilingual = true;

        $languages = Yii::app()->params['languages'];
        $defaultLanguage = Yii::app()->params['defaultLanguage'];
        
        $language = param(Yii::app()->session, $this->getRoute().'-language', $defaultLanguage);
        $language = Yii::app()->request->getQuery('language', $language);
        
        if (!isset($languages[$language])) {
            return $this->badRequest('Выбранный язык не поддерживается');
        }
        
        Yii::app()->session[$this->getRoute().'-language'] = $language;
                     
        $this->languageId = $languages[$language];
    }
    
    public function getRules()
    {
        $key  = $this->module ? ucfirst($this->module->id) : '';
        $key .= ucfirst($this->id);
        
        return [$key . '*', $key . ucfirst($this->action->id)];
    }
    
    public function filters()
    {
        return ['accessControl'];
    }

    public function accessRules()
    {
        if (!Yii::app()->user->checkAccess('AdminModule')) {
            return [['deny']];
        }
        
        if ($this->allowAccess) {
            return [
                [
                    'allow',
                    'users' => ['@'],
                ],
                ['deny'],
            ];
        }

        return [
            [
                'allow',
                'users' => ['@'],
                'roles' => $this->getRules(),
            ],
            ['deny'],
        ];
    }
}
