<?php
namespace Assay\Core {
    interface ICommon
    {
        public static function IsSetEx($valueIfIsset, $valueIfNotIsset);
        public static function SetIfExists($key, &$array, $valueIfNotIsset);
    }
}