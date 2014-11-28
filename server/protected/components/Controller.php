<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class
 */
class Controller extends CController
{
    /**
     * @var string meta tag
     */
    public $description = '';
    /**
     * @var string meta tag
     */
    public $keywords = '';
    /**
     * @var string path to js bundle
     */
    public $jsBundle = 'front.js';
    /**
     * @var string path to css bundle
     */
    public $cssBundle = 'front.css';
    /**
     * @var CPagination
     */
    public $pages;
    /**
     * @var bool support language in URL
     */
    public $translate = true;
    /**
     * @var int
     */
    public $languageId;
    /**
     * @var bool allow access anyway for controller or action
     */
    public $allowAccess = false;
    
    public function init()
    {
        parent::init();
        
        Yii::app()->clientScript->registerMetaTag(
            Yii::app()->getRequest()->getCsrfToken(),
            Yii::app()->getRequest()->csrfTokenName
        );
        
        if (Languages::enabled()) {
            $this->languageId = Yii::app()->params['languages'][Yii::app()->language];
        }
        
        if (Yii::app()->params['setOnlineUsers']) {
            Users::setOnline(Yii::app()->user->id);
        }
    }
    
    /**
     * Load model by id and check owner
     * @param CActiveRecord $model
     * @param int $id
     * @param bool $ownerCheck
     * @return AR
     */
    public function loadModel($model, $id, $ownerCheck = false)
    {
        $model = $model->model()->findByPk($id);
        
        if ($model === null || ($ownerCheck && !$model->isOwner())) {
            return $this->pageNotFound();
        }
        
        return $model;
    }
    
    /**
     * Add title in pageTitle
     * @param CActiveRecord $model
     * @param string $field
     * @param string $default
     * @return void
     */
    public function addTitle($model, $field, $default)
    {
        $this->pageTitle = $this->pageTitle . ' / '. ($model->primaryKey ? $model->{$field} : $default);
    }
    
    /**
     * Set page size
     * @param int $pageSizeMin
     * @param int $pageSizeMax
     * @param string $paramName
     * @return int
     */
    public function pageSize($pageSizeMin, $pageSizeMax, $paramName = 'pagesize')
    {
        $pageSize = Yii::app()->request->getParam($paramName, $pageSizeMin);
        return $pageSize > $pageSizeMax ? $pageSizeMax : $pageSize;
    }
    
    /**
     * Wrapper for try catch
     * @param Closure $func
     * @param Closure $failure function in case of error
     * @return mixed
     */
    public function tryMe(Closure $func, Closure $failure = null)
    {
        try {
            $result = $func->bindTo($this)->__invoke();
        } catch (Exception $e) {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
            
            if ($failure !== null) {
                return $failure->__invoke();
            } else {
                return false;
            }
        }
        
        return $result;
    }
    
    /**
     * Wrapper for transaction
     * @param Closure $func
     * @param Closure $failure function in case of error
     * @return mixed
     */
    public function transaction(Closure $func, Closure $failure = null)
    {
        $transaction = Yii::app()->db->beginTransaction();
        
        try {
            $result = $func->bindTo($this)->__invoke();
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
            
            if ($failure !== null) {
                return $failure->__invoke();
            } else {
                return false;
            }
        }
        
        return $result;
    }
    
    /**
     * Triggers a 404 (Page Not Found) error.
     * @param string $msg
     * @throws CHttpException when invoked.
     */
    public function pageNotFound($msg = 'Страница не найдена')
    {
        throw new CHttpException(404, $msg);
    }

    /**
     * Triggers a 403 (Access Denied) error.
     * @param string $msg
     * @throws CHttpException when invoked.
     */
    public function accessDenied($msg = 'Доступ закрыт')
    {
        throw new CHttpException(403, $msg);
    }

    /**
     * Triggers a 400 (Bad Request) error.
     * @param string $msg 
     * @throws CHttpException when invoked.
     */
    public function badRequest($msg = 'Неверный запрос')
    {
        throw new CHttpException(400, $msg);
    }
    
    /**
     * Response validation errors
     * @param CActiveRecord $model
     * @return void
     */
    public function errors($model)
    {
        $this->response([
            'errors' => $model->getErrors(),
            'model'  => get_class($model)
        ]);
    }
    
    /**
     * Alert
     * @param string $text
     * @return void
     */
    public function alert($text)
    {
        Yii::app()->user->setFlash('alert', $text);
        return $this->redirect($this->createUrl('/alert'));
    }
    
    public function getCssBundle()
    {
        $hash = @filemtime(webroot() . '/public/compiled/' . $this->cssBundle);
        return '/public/compiled/' . ($hash ?: 1) . '/' . $this->cssBundle;
    }
    
    public function getJsBundle()
    {
        $hash = @filemtime(webroot().'/public/compiled/'.$this->jsBundle);
        return '/public/compiled/' . ($hash ?: 1) . '/' . $this->jsBundle;
    }
    
    /**
     * Response
     * @param mixed $data
     * @params bool $isModel if true — CJSON::encode
     * @return void
     */
    public function response($data, $isModel = false)
    {
        header('Content-Type: application/json');
        echo $isModel ? CJSON::encode($data) : json_encode($data);
        Yii::app()->end();
    }



    /**
     * Echo and app end();
     * @param mixed $data
     * @return void
     */
    public function end($data)
    {
        echo $data;
        Yii::app()->end();
    }
    
    public function actionError()
    {
        $error = Yii::app()->errorHandler->error;
        
        if (Yii::app()->request->isAjaxRequest) {
            return $this->response(['error' => $error['code']]);
        } else {
            if ($error) {
                $this->pageTitle = $error['code'];
                switch ($error['code']) {
                    case 403:
                    case 500:
                        $this->render($error['code'], $error);
                        break;
                    default:
                        $this->render('404', $error);
                        break;
                }
            }
        }
    }
}
