<?php

class HttpRequest extends CHttpRequest
{
    public $noCsrfValidationRoutes = [];
    private $requestUri;
 
    public function getRequestUri()
    {
        if ($this->requestUri === null) {
            $this->requestUri = Languages::processLangInUrl(parent::getRequestUri());
        }
 
        return $this->requestUri;
    }
 
    public function getOriginalUrl()
    {
        return $this->getOriginalRequestUri();
    }
 
    public function getOriginalRequestUri()
    {
        return Languages::addLangToUrl($this->getRequestUri());
    }
    
    protected function normalizeRequest()
    {
        //attach event handlers for CSRFin the parent
        parent::normalizeRequest();
        //remove the event handler CSRF if this is a route we want skipped
        
        if (Yii::app() instanceof CConsoleApplication == false) {
            if ($this->enableCsrfValidation) {
                foreach ($this->noCsrfValidationRoutes as $route) {
                    if (strpos($_SERVER['REQUEST_URI'], $route) === 0) {
                        Yii::app()->detachEventHandler('onBeginRequest', [$this, 'validateCsrfToken']);
                    }
                }
            }
        }
    }
}
