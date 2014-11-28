<?php

class Dictionary
{
    const TYPE_VERB      = 0;
    const TYPE_ADVERB    = 1;
    const TYPE_NOUN      = 2;
    const TYPE_ADJECTIVE = 3;


    private static $allowTypes = [
        self::TYPE_VERB,
        self::TYPE_ADVERB,
        self::TYPE_NOUN,
        self::TYPE_ADJECTIVE
    ];

    private $redis = null;

    function __construct()
    {
        $this->redis = Yii::app()->redis->select(PRedis::DB_DICTIONARY);
    }

    /**
     * @param integer $type
     * @return bool
     * @throws Exception
     */
    private function checkTypeExist($type)
    {
        if (in_array($type, self::$allowTypes)) {
            return true;
        } else {
            throw new Exception("$type is not allowed");
        }
    }

    /**
     * @param integer $type
     * @param string  $title
     * @return integer
     */
    public function addItem($type, $title)
    {
        $title = trim($title);
        if (!$this->itemExist($type, $title) && $title != null) {
            return $this->redis->sAdd("$type", $title);
        }
        return 0;
    }

    /**
     * @param integer $type
     * @param string  $title
     * @return bool
     */
    private function itemExist($type, $title)
    {
        if ($this->redis->sismember("$type", $title) == 1) {
            return true;
        }
        return false;
    }

    /**
     * @param integer $type
     * @return string
     * @throws Exception
     */
    private function randItemByType($type)
    {
        if ($this->checkTypeExist($type)) {
            return $this->redis->sRandmember("$type");
        }
        return null;
    }

    /**
     * @param integer $type
     * @param integer $count
     * @return string[]
     */
    private function randItemsByType($type, $count)
    {
        $items = [];
        for ($i = 0; $i < $count; $i++) {
            $items []= $this->randItemByType($type);
        }
        return $items;
    }

    /**
     * @param string|null $title
     * @return int
     */
    public function addVerb($title = null)
    {
        return $this->addItem(self::TYPE_VERB, $title);
    }
    
    /**
     * @param string|null $title
     * @return int
     */
    public function addAdverb($title = null)
    {
        return $this->addItem(self::TYPE_ADVERB, $title);
    }
    
    /**
     * @param string|null $title
     * @return int
     */
    public function addNoun($title = null)
    {
        return $this->addItem(self::TYPE_NOUN, $title);
    }
    
    /**
     * @param string|null $title
     * @return int
     */
    public function addAdjective($title = null)
    {
        return $this->addItem(self::TYPE_ADJECTIVE, $title);
    }

    /**
     * @param integer $count
     * @return string[]
     */
    public function randVerbs($count = 0)
    {
        return $this->randItemsByType(self::TYPE_VERB, $count);
    }
    
    /**
     * @param integer $count
     * @return string[]
     */
    public function randAdverbs($count = 0)
    {
        return $this->randItemsByType(self::TYPE_ADVERB, $count);
    }

    /**
     * @param integer $count
     * @return string[]
     */
    public function randNouns($count = 0)
    {
        return $this->randItemsByType(self::TYPE_NOUN, $count);
    }

    /**
     * @param integer $count
     * @return string[]
     */
    public function randAdjectives($count = 0)
    {
        return $this->randItemsByType(self::TYPE_ADJECTIVE, $count);
    }
}