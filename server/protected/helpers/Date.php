<?php

class Date
{
    /**
     * Validate MySQL DateTime
     * @param string $date
     * @return bool
     */
    public static function validateDateTime($date)
    {
        if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date)
        || preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$/", $date)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Return current DateTime
     * @return string
     */
    public static function now()
    {
        return date('Y-m-d H:i:s');
    }
}
