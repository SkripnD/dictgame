<?php

class Parser
{
    private static $urls = [
        Dictionary::TYPE_VERB      => 'http://dict.ruslang.ru/freq.php?act=show&dic=freq_v&title=%D7%E0%F1%F2%EE%F2%ED%FB%E9%20%F1%EF%E8%F1%EE%EA%20%E3%EB%E0%E3%EE%EB%EE%E2',
        Dictionary::TYPE_ADJECTIVE => 'http://dict.ruslang.ru/freq.php?act=show&dic=freq_adj&title=%D7%E0%F1%F2%EE%F2%ED%FB%E9%20%F1%EF%E8%F1%EE%EA%20%E8%EC%E5%ED%20%EF%F0%E8%EB%E0%E3%E0%F2%E5%EB%FC%ED%FB%F5',
        Dictionary::TYPE_NOUN      => 'http://dict.ruslang.ru/freq.php?act=show&dic=freq_s&title=%D7%E0%F1%F2%EE%F2%ED%FB%E9%20%F1%EF%E8%F1%EE%EA%20%E8%EC%E5%ED%20%F1%F3%F9%E5%F1%F2%E2%E8%F2%E5%EB%FC%ED%FB%F5',
        Dictionary::TYPE_ADVERB    => 'http://dict.ruslang.ru/freq.php?act=show&dic=freq_adv&title=%D7%E0%F1%F2%EE%F2%ED%FB%E9%20%F1%EF%E8%F1%EE%EA%20%ED%E0%F0%E5%F7%E8%E9%20%E8%20%EF%F0%E5%E4%E8%EA%E0%F2%E8%E2%EE%E2'
    ];

    /**
     * @param integer $type
     * @return bool
     * @throws Exception
     */
    private function checkTypeExist($type)
    {

        if (array_key_exists($type, self::$urls)) {
            return true;
        }
        throw new Exception("$type is not exist");
    }

    /**
     * @param string $url
     * @return bool|string
     */
    private function getPageContent($url)
    {
        $ch = curl_init($url);
        if ($ch === false) {
            return false;
        }
        $options = [
            CURLOPT_CUSTOMREQUEST  =>"GET",     //set request type post or get
            CURLOPT_POST           => false,    //set to GET
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        ];
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        curl_close($ch);
        return $content;
    }

    private function getWordsFromDictHtml($html)
    {
        Yii::import('ext.phpQuery.phpQuery');
        $words      = [];

        $doc = phpQuery::newDocument($html);
        foreach(pq('table table tr td:nth-child(2)')->elements as $element) {
            $words []= strtolower(trim($element->textContent));
        }
        $words = array_splice($words, 1);
        return $words;
    }


    private function parsePage($type)
    {
        if ($this->checkTypeExist($type) &&
            $html = $this->getPageContent(self::$urls[$type])
        ) {
            $words = $this->getWordsFromDictHtml($html);
            $dict = new Dictionary();
            foreach ($words as $word) {
                $dict->addItem($type, $word);
            }
        }
    }

    public function parseVerbs()
    {
        $this->parsePage(Dictionary::TYPE_VERB);
    }

    public function parseAdverbs()
    {
        $this->parsePage(Dictionary::TYPE_ADVERB);
    }

    public function parseNouns()
    {
        $this->parsePage(Dictionary::TYPE_NOUN);
    }

    public function parseAdjectives()
    {
        $this->parsePage(Dictionary::TYPE_ADJECTIVE);
    }
}