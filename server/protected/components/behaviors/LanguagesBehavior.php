<?php

/**
 * Set languageId
 */
class LanguagesBehavior extends CActiveRecordBehavior
{
    public function beforeSave($event)
    {
        if ($this->getOwner()->isNewRecord) {
            $this->getOwner()->languageId = Yii::app()->controller->languageId;
        }

        return true;
    }
}
