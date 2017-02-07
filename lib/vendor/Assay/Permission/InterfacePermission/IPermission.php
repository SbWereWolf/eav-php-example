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

        public function getAllow(array $args);

        public function getGreetingsRole(): string;

        public function getMode(): string;

        public function getPaging(): string;

        public function getUserId(): string;

        public function getProfileId(): string;

        public function getCompanyFilter(): string;

        public function set(string $object,string $action,string $sessionId): array;
    }
}