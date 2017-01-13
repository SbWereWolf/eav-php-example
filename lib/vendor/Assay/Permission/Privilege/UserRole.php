<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:10
 */
namespace Assay\Permission\Privilege {

    use Assay\Core\Common;
    use Assay\Core\Entity;

    class UserRole extends Entity implements IUserRole, IAuthorizeProcess
    {
        /** @var string имя таблицы роли */
        const TABLE_NAME_ROLE = 'role';

        /** @var string имя таблицы юзер-роль*/
        const TABLE_NAME_USER_ROLE = 'user_role';

        /** @var string ссылка на учётную запись */
        public $userId;

        public function __construct(string $userId)
        {
            $this->userId = $userId;
        }

        public function grantRole(string $role):bool
        {
            $dbh = new \PDO("pgsql:dbname=".Common::DB_NAME.";host=".Common::DB_HOST, Common::DB_LOGIN, Common::DB_PASSWORD);
            $sth = $dbh->prepare("
                    INSERT INTO 
                        ".self::TABLE_NAME_USER_ROLE."
                    (
                        ".IUser::EXTERNAL_ID.", ".self::EXTERNAL_ID."
                    ) 
                    VALUES 
                        (
                            :USER_ID,:USER_ROLE_ID
                        )
                ");
            $sth->bindValue(':USER_ID', $this->userId, \PDO::PARAM_INT);
            $sth->bindValue(':USER_ROLE_ID', $role, \PDO::PARAM_INT);
            $sth->execute();
            $result = ($sth->errorCode() == "00000")?true:false;
            return $result;
        }

        public function revokeRole(string $role):bool
        {
            $dbh = new \PDO("pgsql:dbname=".Common::DB_NAME.";host=".Common::DB_HOST, Common::DB_LOGIN, Common::DB_PASSWORD);
            $sth = $dbh->prepare("
                    DELETE FROM
                        ".self::TABLE_NAME_USER_ROLE."
                    WHERE 
                        ".IUser::EXTERNAL_ID." = :USER_ID AND ".self::EXTERNAL_ID." = :USER_ROLE_ID
                ");
            $sth->bindValue(':USER_ID', $this->userId, \PDO::PARAM_INT);
            $sth->bindValue(':USER_ROLE_ID', $role, \PDO::PARAM_INT);
            $sth->execute();
            $result = ($sth->errorCode() == "00000")?true:false;
            return $result;
        }

        public function userAuthorization(string $process, string $object):bool
        {
            $result = false;
            return $result;
        }
    }
}