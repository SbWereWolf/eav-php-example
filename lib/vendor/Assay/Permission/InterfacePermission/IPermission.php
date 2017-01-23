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
        const OBJECT = 'object';
        const ACTION = 'action';
        const SESSION_ID = 'session_id';
        const IS_ALLOW = 'is_allow';

        public function checkPrivilege(array $args):array;

        public function getAllow(array $args): string;

        public function set(string $object,string $action,string $sessionId): array;
    }
}