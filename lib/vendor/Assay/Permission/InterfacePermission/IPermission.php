<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 20.01.17
 * Time: 16:52
 */

namespace Assay\Permission\InterfacePermission {
    interface IPermission
    {
        public function checkPrivilege(array $args):array;

        public function set(array $args):array;

        public function get(string $key,array $args):string;
    }
}