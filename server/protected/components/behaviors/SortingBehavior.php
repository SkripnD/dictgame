<?php

/**
 * Manual sorting
 */
class SortingBehavior extends CActiveRecordBehavior
{
    /**
     * @var bool get last pos value
     */
    public $calculatePosition = true;
    
    public $multilingual = false;

    public function beforeSave($event)
    {
        if ($this->getOwner()->isNewRecord && $this->calculatePosition) {
            $query = Yii::app()->db->createCommand()
                ->select('MAX(`pos`) + 1 AS new')
                ->from($this->getOwner()->tableName());
                
            if ($this->multilingual) {
                $query->where(
                    'languageId = :languageId',
                    [':languageId' => Yii::app()->controller->languageId]
                );
            }
            
            $pos = $query->queryScalar();
            
            $this->getOwner()->pos = $pos ? $pos : 1;
        }

        return true;
    }
    
    /**
     * Set position for all records
     * @param array $ids
     * @return void
     */
    public function multisort($ids)
    {
        foreach ($ids as $pos => $id) {
            $this->getOwner()->updateByPk($id, ['pos' => $pos + 1]);
        }
    }
    
    /**
     * Move position
     * @param string $mode up or down
     * @return void
     */
    public function move($mode)
    {
        $current = $this->getOwner();
        
        if ($mode == 'up') {
            $partner = $this->getNext($current->pos);
        } else {
            $partner = $this->getPrevious($current->pos);
        }
            
        if ($partner) {
            $primaryKey = $this->getOwner()->tableSchema->primaryKey;
            $this->getOwner()->updateByPk($partner->{$primaryKey}, ['pos' => $current->pos]);
            $this->getOwner()->updateByPk($current->{$primaryKey}, ['pos' => $partner->pos]);
        }
    }
    
    private function getNext($pos)
    {
        $where = ['and', '`pos` > :pos'];
        $whereData = [':pos' => $pos];
        
        if ($this->multilingual) {
            $where[] = ['and', 'languageId = :languageId'];
            $whereData[':languageId'] = Yii::app()->controller->languageId;
        }
        
        return Yii::app()->db->createCommand()
            ->select('id, pos')
            ->from($this->getOwner()->tableName())
            ->where($where, $whereData)
            ->order('pos ASC')
            ->limit(1)
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryRow();
    }
    
    private function getPrevious($pos)
    {
        $where = ['and', '`pos` < :pos'];
        $whereData = [':pos' => $pos];
        
        if ($this->multilingual) {
            $where[] = ['and', 'languageId = :languageId'];
            $whereData[':languageId'] = Yii::app()->controller->languageId;
        }
        
        return Yii::app()->db->createCommand()
            ->select('id, pos')
            ->from($this->getOwner()->tableName())
            ->where($where, $whereData)
            ->order('pos DESC')
            ->limit(1)
            ->setFetchMode(PDO::FETCH_OBJ)
            ->queryRow();
    }
}
