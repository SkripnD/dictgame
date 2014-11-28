<?php

/**
 * Model is the customized base model class.
 * All model classes for this application should extend from this base class.
 */
class Model extends CActiveRecord
{
    public $isNew = false;

    private $fetchMode = PDO::FETCH_CLASS;

    public function isOwner()
    {
        $isAdmin = !Yii::app()->user->isGuest && Yii::app()->user->model->isAdmin();
        
        if ($isAdmin || Yii::app()->user->id == $this->userId) {
            return true;
        }

        return false;
    }

    protected function beforeSave()
    {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->isNew = true;
            }

            return true;
        }

        return false;
    }

    /**
     * Validator to call methods on models
     * This is the 'callValidator' validator as declared in rules().
     */
    public function callValidator($attribute, $params)
    {
        $model = new $params[0];
        $model->{$params[1]}($attribute, $params, $this);
    }

    public function scopes()
    {
        return [
            'published' => [
                'condition' => 't.active = 1',
            ],

            'translated' => [
                'condition' => 't.languageId = :langId',
                'params' => [':langId' => @Yii::app()->controller->languageId]
            ]
        ];
    }
    
    public function order($column, $ordermode = 'ASC')
    {
        if ($column !== null) {
            $this->getDbCriteria()->order = $column . ' ' . $ordermode;
        }

        return $this;
    }

    public function search($key = null, $columns = 't.title')
    {
        if ($key !== null) {

            if (!is_array($columns)) {
                $columns = [$columns];
            }

            $conditions = [];

            foreach ($columns as $column) {
                $column = Yii::app()->db->quoteColumnName($column);
                $conditions[] = $column . ' LIKE :key';
            }

            $this->getDbCriteria()->mergeWith([
                'condition' => implode(' OR ', $conditions),
                'params'    => [':key' => '%' . $key . '%'],
            ]);
        }

        return $this;
    }

    public function active($active = null)
    {
        if ($active !== null) {
            $this->getDbCriteria()->mergeWith([
                'condition' => 't.active = :active',
                'params'    => [':active' => $active],
            ]);
        }

        return $this;
    }

    public function languageId($languageId = null)
    {
        if ($languageId !== null) {
            $this->getDbCriteria()->mergeWith([
                'condition' => 't.languageId = :languageId',
                'params'    => [':languageId' => $languageId],
            ]);
        }

        return $this;
    }

    public function limit($limit)
    {
        $this->getDbCriteria()->mergeWith([
            'limit' => $limit
        ]);

        return $this;
    }

    public function offset($offset)
    {
        $this->getDbCriteria()->mergeWith([
            'offset' => $offset
        ]);

        return $this;
    }

    /**
     * Filter by custom column
     * @param string $column
     * @param mixed $value
     * @return $this
     */
    public function filterBy($column, $value = null)
    {
        $param = str_replace('.', '_', $column);
        $column = Yii::app()->db->quoteColumnName($column);

        $this->getDbCriteria()->mergeWith([
            'condition' => $column . ' = :' . $param,
            'params'    => [':' . $param => $value],
        ]);

        return $this;
    }
    
    public function uniqueMultiple($attribute, $params = array())
    {
        $attributes = explode('+', $attribute);
        
        if (!$this->hasErrors()) {
            $params = [];
            $condition = '';
            foreach ($attributes as $attribute) {
                $condition .= 't.'.$attribute.' = :'.$attribute.' AND ';
                $params['params'][':'.$attribute] = $this->{$attribute};
            }

            if ($this->hasAttribute('languageId')) {
                $condition .= 't.languageId = :languageId';
                $params['params'][':languageId'] = Yii::app()->controller->languageId;
            }
            
            $params['condition'] = $condition;
            
            $validator = CValidator::createValidator('unique', $this, $attribute, ['criteria' => $params]);
            $validator->validate($this, array($attribute));
        }
    }
    
    public function checkValueFromModel($attribute, $params = array())
    {
        if (!$this->hasErrors()) {
            $model = new $params['model'];
    
            $value = Yii::app()->db->createCommand()
                ->select($model->tableSchema->primaryKey)
                ->from($model->tableName())
                ->where($params['field'].' = :'.$params['field'], [':'.$params['field'] => $this->{$attribute}])
                ->queryScalar();
                
            $this->{$attribute} = $value ? $value : null;
         
            if ($this->{$attribute} === null && isset($params['required']) && $params['required']) {
                $this->addError($attribute, 'Неверное значение');
            }
        }
    }

    /**
     * Get all records
     * @param string|int $excludeId for exclude from the data
     * @return array
     */
    public function getAll($excludeId = null, $order = 'title ASC')
    {
        $list = $this->findAll(['order' => $order]);
        $primaryKey = $this->tableSchema->primaryKey;

        $arr = [];
        foreach ($list as $item) {
            if ($excludeId !== null && $item->{$primaryKey} == $excludeId) {
                continue;
            }
            $arr[$item->{$primaryKey}] = $item;
        }

        return $arr;
    }

    /**
     * Get count rows by DbCriteria
     */
    public function getCount()
    {
        $criteria = $this->getDbCriteria();
        $select = $this->getDbCriteria()->select;

        $primaryKey = Yii::app()->db->quoteColumnName('t.' . $this->tableSchema->primaryKey);
        $this->getDbCriteria()->select = $primaryKey;

        $count = $this->count($this->getDbCriteria());

        $this->setDbCriteria($criteria);
        $this->getDbCriteria()->select = $select;

        return $count;
    }

    /**
     * Get the result through the query builder
     * @param string $select
     * @param string $order
     * @param string $ordermode
     * @return array
     */
    public function get($select = 't.*', $order = null, $ordermode = 'DESC')
    {
        $this->getDbCriteria()->mergeWith([
            'select' => $select,
        ]);

        if (!empty($order)) {
            $this->getDbCriteria()->mergeWith([
                'order' => $order . ' ' . $ordermode,
            ]);
        }

        if ($this->fetchMode == PDO::FETCH_CLASS) {
            return $this->query($this->getDbCriteria(), true);
        } else {
            return $this->getCommandBuilder()
                ->createFindCommand($this->tableName(), $this->getDbCriteria())
                ->setFetchMode($this->fetchMode)
                ->queryAll();
        }
    }

    public function asArray()
    {
        $this->fetchMode = PDO::FETCH_ASSOC;
        return $this;
    }

    public function asObjects()
    {
        $this->fetchMode = PDO::FETCH_OBJ;
        return $this;
    }
}
