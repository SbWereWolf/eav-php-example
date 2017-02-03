<?php
namespace Assay\Communication\Profile {
    class Common
    {
//получаем список полей таблицы
        public static function getFieldsList($class): array
        {
         //   $class = get_called_class();
            $refl = new \ReflectionClass( $class);
            $params = $refl->getConstants();
            $strangeParams = [];
            foreach ($params as $key => $value) {
                if (is_array($value)) continue;
                $stringKey = (string)$key;
                $stringValue = (string)$value;
                $strangeParams[$stringValue] = $stringKey;
            }

            unset($strangeParams[$class::TABLE_NAME]);
            unset($strangeParams[$class::EMPTY_VALUE]);
            unset($strangeParams[$class::EMPTY_OBJECT]);
            unset($strangeParams[$class::DEFINE_AS_HIDDEN]);
            unset($strangeParams[$class::DEFINE_AS_NOT_HIDDEN]);
            unset($strangeParams[$class::DEFAULT_IS_HIDDEN]);
            unset($strangeParams[$class::WORD_DIVIDER]);
 //           unset($strangeParams[$class::GREETINGS_ROLE]);
            unset($strangeParams[$class::EXTERNAL_ID]);
//print_r($strangeParams);

            return $strangeParams;
        }

        //camelCase
        public static function camelCase($propertyName, array $noStrip = [], $wordDivider)
        {
            // non-alpha and non-numeric characters become spaces
            //  $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
            $propertyName = trim($propertyName);
            // uppercase the first character of each word
            $propertyName = ucwords($propertyName, $wordDivider);
            $propertyName = str_replace(" ", "", $propertyName);
            $propertyName = str_replace($wordDivider, "", $propertyName);
            $propertyName = lcfirst($propertyName);

            return $propertyName;
        }
    }
}
?>