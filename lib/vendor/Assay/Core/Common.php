<?php
namespace Assay\Core {
    /**
     * Реализация интерфейса для методов общего назначения
     */
    class Common implements ICommon
    {
        const EMPTY_VALUE = '';
        const EMPTY_OBJECT = null;

        public static function setIfExists($key, &$array, $valueIfNotIsset)
        {
            $value = $valueIfNotIsset;
            $maySet = array_key_exists($key, $array);
            if ($maySet) {
                $value = self::isSetEx($array[$key], $valueIfNotIsset);
            }
            return $value;
        }

        public static function isSetEx($valueIfIsset, $valueIfNotIsset)
        {
            $value = isset($valueIfIsset) ? $valueIfIsset : $valueIfNotIsset;
            return $value;
        }
    }
}
