<?php

/**
 * Represents the persistent state for a Web application user
 */
class WebUser extends CWebUser
{
    private $model = null;

    public function getModel()
    {
        if (!$this->isGuest && $this->model === null) {
            $this->model = Users::model()->findByPk($this->id);
            
            if ($this->model == false || !$this->model->active) {
                Yii::app()->user->logout();
                Yii::app()->user->loginRequired();
            }
        }

        return $this->model;
    }
    
    public function getRole()
    {
        if ($user = $this->getModel()) {
            return $user->role;
        }
    }
    
   /**
    * This method is called after the user is successfully logged in.
    * You may override this method to do some postprocessing (e.g. log the user
    * login IP and time; load the user permission information).
    * @param boolean $fromCookie whether the login is based on cookie.
    * @since 1.1.3
    */
    public function afterLogin($fromCookie)
    {
        parent::afterLogin($fromCookie);

        $user = Users::model()->findByPk($this->getId());

        if ($user && $user->isAdmin()) {
            $this->setIsAdmin(true);
        }
    }
     
    public function setIsAdmin($value)
    {
        $this->setState('ACCESS_ADMIN', $value);
    }
  
   /**
    * @return boolean whether the user is a superuser.
    */
    public function getIsAdmin()
    {
        return $this->getState('ACCESS_ADMIN');
    }
    
    public function checkAccess($operation, $params = array(), $allowCaching = true)
    {
        return $this->getIsAdmin() ? true : parent::checkAccess($operation, $params, $allowCaching);
    }
}
