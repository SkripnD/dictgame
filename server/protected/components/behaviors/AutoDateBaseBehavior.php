<?php

/**
 * Set date/datetime created and modified
 */
class AutoDateBaseBehavior extends CActiveRecordBehavior
{
    public $created  = 'dateCreate';
    public $modified = 'dateUpd';

    public function beforeValidate($event)
    {
        if ($this->getOwner()->isNewRecord) {
            $this->getOwner()->{$this->created} = Date::now();
        }
        
        $this->getOwner()->{$this->modified} = Date::now();

        return true;
    }
}
