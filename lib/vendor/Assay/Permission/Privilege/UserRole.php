<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:10
 */
namespace Assay\Permission\Privilege {

    use Assay\Core\Entity;

    class UserRole extends Entity implements IUserRole, IAuthorizeProcess
    {
        /** @var string ссылка на учётную запись */
        public $userId;

        public function __construct(string $userId)
        {
            $this->userId = $userId;
        }

        public function grantRole(string $role):bool
        {
        }

        public function revokeRole(string $role):bool
        {
        }

        public function userAuthorization(string $process, string $object):bool
        {
            $result = false;
            return $result;
        }
    }
}