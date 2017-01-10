<?php
namespace Assay\Core {
    class Common implements ICommon
    {
        const EMPTY_VALUE = '';
        const EMPTY_OBJECT = null;

        public static function IsSetEx($valueIfIsset, $valueIfNotIsset)
        {
            $value = isset($valueIfIsset) ? $valueIfIsset : $valueIfNotIsset;
            return $value;
        }

        public static function SetIfExists($key, &$array, $valueIfNotIsset)
        {
            $value = $valueIfNotIsset;
            $maySet = array_key_exists($key, $array);
            if ($maySet) {
                $value = self::IsSetEx($array[$key], $valueIfNotIsset);
            }
            return $value;
        }
    }
}