<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 18.01.17
 * Time: 15:55
 */
namespace Assay\BusinessLogic {

    use Assay\Core\Common;
    use Assay\Permission\InterfacePermission\Permission;

    class UserInreface implements IUserInreface
    {
        private $permission;

        function __construct()
        {
            $this->permission = new Permission();
        }

        public function getPaging(): string
        {
            $result = $this->permission->getPaging();
            return $result;
        }

        public function getGreetingsRole(): string
        {
            $result = $this->permission->getGreetingsRole();
            return $result;
        }

        public function getMode(): string
        {
            $result = $this->permission->getMode();
            return $result;
        }

        public function getProfileId(): string
        {
            $result = $this->permission->getProfileId();
            return $result;
        }

        public function getUserId(): string
        {
            $result = $this->permission->getUserId();
            return $result;
        }

        public function getCompanyFilter(): string
        {
            $result = $this->permission->getCompanyFilter();
            return $result;
        }
    }
}