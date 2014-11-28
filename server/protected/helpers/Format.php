<?php

class Format
{
    /**
     * Money format
     * @param int $val
     * @return string
     */
    public static function money($val)
    {
        return is_numeric($val) ? number_format($val, 2, '.', ' ') : '0.00';
    }

    /**
     * Str to lower
     * @params string $str
     * @return string
     */
    public static function lower($str)
    {
        return mb_strtolower($str, 'utf-8');
    }
    
    /**
     * Str to upper
     * @params string $str
     * @return string
     */
    public static function upper($str)
    {
        return mb_strtoupper($str, 'utf-8');
    }
    
    /**
     * Date format
     * @param string $date
     * @param string $format
     * @return string
     */
    public static function date($date, $format = "dd.MM.yyyy HH:mm")
    {
        $timestamp = strtotime($date);
        
        if ($timestamp == false || $timestamp < 0) {
            return '—';
        }
        
        if (date_format(date_create($date), 'Hi') === '0000') {
            $format = str_replace('HH:mm:ss', '', $format);
            $format = str_replace('HH:mm', '', $format);
        }
            
        $date = Yii::app()->dateFormatter->format($format, $timestamp);
    
        return mb_convert_case($date, MB_CASE_TITLE, 'UTF-8');
    }
}
