<?php
namespace Assay\Core {
    /**
     * Реализация интерфейса для методов общего назначения
     */
    class Common implements ICommon
    {
        public static function setIfExists($key, &$array, $valueIfNotIsset)
        {

            $isArray = is_array($array);

            $maySet = false;
            if($isArray){
                $maySet = array_key_exists($key, $array);
            }

            $value = $valueIfNotIsset;
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
