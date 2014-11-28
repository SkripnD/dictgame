<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $id;
    public $active;
    
    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate($identityField = 'login')
    {
        $user = Users::model()->find($identityField.'=?', [strtolower($this->username)]);
        
        if ($user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif (Users::hashPassword($this->password, $user->salt) !== $user->password) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->id = $user->id;
            $this->active = $user->active;
            $this->errorCode = self::ERROR_NONE;
        }
    
        return $this->errorCode == self::ERROR_NONE;
    }

    /**
     * @return integer the ID of the user record
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @param integer the ID of the user record
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function forceAuthenticate($id)
    {
        $this->id = $id;
        return true;
    }
}
