<?php

/**
 * Files
 */
class FilesBehavior extends CActiveRecordBehavior
{
    /**
     * @var array image sizes
     */
    public $imagesizes = null;
    /**
     * @var array mime types
     */
    public $mimetypes = null;
    /**
     * @var array max file size
     */
    public $filesize = null;
    /**
     * @var array files ownerType => […]
     */
    public $files = null;
    /**
     * @var array bind ownerType => field
     */
    public $bind = [];
    /**
     * @var string mask of owner, e.g. OWNER_TYPE_NEWS_
     * to remove all related files on a mask
     */
    public $ownerTypeMask = null;
    
    public $result = [];
    
    /**
     * Delete all related files
     */
    public function beforeDelete($event)
    {
        $ownerTypes = [];
        
        if ($this->ownerTypeMask !== null) {
            // get ownerTypes by mask
            $types = Arr::filterMaskKey('Files', '/'.$this->ownerTypeMask.'/');
            
            foreach ($types as $type) {
                $ownerTypes[] = $type['value'];
            }
        }

        if (count($this->bind)) {
            foreach ($this->bind as $ownerType => $field) {
                if (!in_array($ownerType, $ownerTypes)) {
                    $ownerTypes[] = $ownerType;
                }
            }
        }
        
        $this->deleteFilesByOwnerTypes($ownerTypes);
        
        parent::beforeDelete($event);
    }
    
    public function beforeValidate($event)
    {
        if (count($this->bind)) {
            // check files
            foreach ($this->bind as $ownerType => $field) {
                if ($this->getOwner()->{$field} !== null) {

                    $files = $this->getOwner()->{$field};
                    
                    if (is_array($files) && isset($files[$ownerType])) {
                        $files = $files[$ownerType];
                    }
                    
                    $result = Files::model()->check(
                        $this->getOwner()->primaryKey,
                        $ownerType,
                        $files
                    );
                    
                    // if exist field — set field
                    if ($this->getOwner()->hasAttribute($field)) {
                        $this->getOwner()->{$field} = $result ? $result->getFullPath() : '';
                    }

                    $this->result[$ownerType] = $result;
                }
            }
        }
    }
    
    public function afterSave($event)
    {
        foreach ($this->result as $ownerType => $result) {
            Files::model()->bind(
                $this->getOwner()->primaryKey,
                $ownerType,
                $result
            );
        }
        
        parent::afterSave($event);
    }
    
    /**
     * Get files by ownerType
     * @param int $ownerType
     * @param string $order
     * @param string $ordermode
     * @return array
     */
    public function getFiles($ownerType, $order = 'pos = 0, pos', $ordermode = 'ASC')
    {
        return $this->getOwner()->primaryKey ? Files::model()
            ->ownerId($this->getOwner()->primaryKey)
            ->ownerType($ownerType)
            ->get('t.*', $order, $ordermode) : [];
    }
    
    /**
     * Get rules by ownerType
     * @param int $ownerType
     * @return array
     */
    public function getRulesForFiles($ownerType)
    {
        $rules = [];
        
        if (isset($this->imagesizes[$ownerType])) {
            $rules['imagesizes'] = $this->imagesizes[$ownerType];
        }
        
        if (isset($this->mimetypes[$ownerType])) {
            $rules['mimetypes'] = $this->mimetypes[$ownerType];
        }
        
        if (isset($this->filesize[$ownerType])) {
            $rules['filesize'] = $this->filesize[$ownerType];
        }
        
        return $rules;
    }
    
    /**
     * Delete files by owner types
     * @param array $ownerTypes
     * @return void
     */
    public function deleteFilesByOwnerTypes($ownerTypes)
    {
        foreach ($ownerTypes as $ownerType) {
            $files = Files::model()->findAll(
                'ownerId = :ownerId AND ownerType = :ownerType',
                [':ownerId' => $this->getOwner()->primaryKey, ':ownerType' => $ownerType]
            );
            
            foreach ($files as $file) {
                $file->delete();
            }
        }
    }
}
