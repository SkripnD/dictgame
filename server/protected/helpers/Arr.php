<?php 

class Arr
{
    /**
     * Filter mask key
     * @param string $className
     * @param string $mask
     * @param array $haystack
     * @return array
     */
    public static function filterMaskKey($className, $mask)
    {
        $refl = new ReflectionClass($className);
        $haystack = $refl->getConstants();
        
        $arr = [];
        foreach ($haystack as $key => $val) {
            if (preg_match($mask, $key)) {
                $arr[] = ['key' => $key, 'value' => $val];
            }
        }
        return $arr;
    }
    
    /**
     * Make an associative array from values
     * @param array $array
     * @return array
     */
    public static function makeAssocFromValues($array)
    {
        return $array && count($array) ? array_combine(array_values($array), $array) : [];
    }
}
