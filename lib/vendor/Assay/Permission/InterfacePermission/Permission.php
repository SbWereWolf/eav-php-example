<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 20.01.17
 * Time: 16:56
 */
namespace Assay\Permission\InterfacePermission {

    use Assay\Core\ICommon;

    Class Permission implements IPermission {
        public function checkPrivilege(array $args): array
        {
            $result = ICommon::EMPTY_ARRAY;
            return $result;
        }

        public function getAllow(array $args): string
        {
            $result = ICommon::EMPTY_VALUE;
            return $result;
        }

        public function set(string $object,string $action,string $sessionId): array
        {
            $result = ICommon::EMPTY_ARRAY;
            return $result;
        }
    }
}