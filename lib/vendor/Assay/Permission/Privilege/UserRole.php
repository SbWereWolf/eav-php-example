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
        /** @var string имя таблицы */
        const TABLE_NAME = 'user_business_role';

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
                        ".self::TABLE_NAME."
                    (
                        ".IUser::EXTERNAL_ID.", ".IUserRole::ROLE."
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
                        ".self::TABLE_NAME."
                    WHERE 
                        ".IUser::EXTERNAL_ID." = :USER_ID AND ".IUserRole::ROLE." = :USER_ROLE_ID
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
            $dbh = new \PDO("pgsql:dbname=".Common::DB_NAME.";host=".Common::DB_HOST, Common::DB_LOGIN, Common::DB_PASSWORD);
            $sth = $dbh->prepare("
                    SELECT 
                        *
                    FROM 
                        business_role_rule AS brr, user_business_role AS ubr
                    WHERE 
                        brr.process = :PROCESS AND brr.object = :OBJECT AND brr.business_role_id=ubr.business_role_id AND ubr.account_id=:ACCOUNT_ID
                ");
            $sth->bindValue(':PROCESS', $process, \PDO::PARAM_STR);
            $sth->bindValue(':OBJECT', $object, \PDO::PARAM_STR);
            $sth->bindValue(':ACCOUNT_ID', $this->userId, \PDO::PARAM_STR);
            $sth->execute();
            $rows = $sth->fetchAll(\PDO::FETCH_ASSOC);
            if (count($rows) && $sth->errorCode() == "00000") {
                $result = true;
            }
            return $result;
        }
    }
}