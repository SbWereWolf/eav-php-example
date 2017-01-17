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
    use Assay\DataAccess\SqlReader;

    class UserRole extends Entity implements IUserRole, IAuthorizeProcess
    {

        /** @var string название таблицы */
        const TABLE_NAME = 'user_business_role';
        /** @var string ссылка на учётную запись */
        public $userId;

        public function __construct(string $userId)
        {
            $this->userId = $userId;
            $this->tablename = self::TABLE_NAME;
        }

        public function grantRole(string $role):bool
        {
            $result = false;
            $sqlReader = new SqlReader();
            $user_id[SqlReader::QUERY_PLACEHOLDER] = ':USER_ID';
            $user_id[SqlReader::QUERY_VALUE] = $this->userId;
            $user_id[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $role_field[SqlReader::QUERY_PLACEHOLDER] = ':USER_ROLE_ID';
            $role_field[SqlReader::QUERY_VALUE] = $role;
            $role_field[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $arguments[SqlReader::QUERY_TEXT] = "
                INSERT INTO 
                    ".$this->tablename."
                (
                    ".IUser::EXTERNAL_ID.", ".IUserRole::ROLE."
                ) 
                VALUES 
                    (
                        ".$user_id[SqlReader::QUERY_PLACEHOLDER].",".$role_field[SqlReader::QUERY_PLACEHOLDER]."
                    )
            ";
            $arguments[SqlReader::QUERY_PARAMETER] = [$user_id,$role_field];
            $result_sql = $sqlReader ->performQuery($arguments);
            if ($result_sql[SqlReader::ERROR_INFO][0] == Common::NO_ERROR) {
                $rows = $result_sql[SqlReader::RECORDS];
                $result = (count($rows) > 0)?true:false;
            }
            return $result;
        }

        public function revokeRole(string $role):bool
        {
            $result = false;
            $sqlReader = new SqlReader();
            $user_id[SqlReader::QUERY_PLACEHOLDER] = ':USER_ID';
            $user_id[SqlReader::QUERY_VALUE] = $this->userId;
            $user_id[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $role_field[SqlReader::QUERY_PLACEHOLDER] = ':USER_ROLE_ID';
            $role_field[SqlReader::QUERY_VALUE] = $role;
            $role_field[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $arguments[SqlReader::QUERY_TEXT] = "
                DELETE FROM
                        ".$this->tablename."
                    WHERE 
                        ".IUser::EXTERNAL_ID." = ".$user_id[SqlReader::QUERY_PLACEHOLDER]." AND ".IUserRole::ROLE." = ".$role_field[SqlReader::QUERY_PLACEHOLDER]."
            ";
            $arguments[SqlReader::QUERY_PARAMETER] = [$user_id,$role_field];
            $result_sql = $sqlReader ->performQuery($arguments);
            if ($result_sql[SqlReader::ERROR_INFO][0] == Common::NO_ERROR) {
                $rows = $result_sql[SqlReader::RECORDS];
                $result = (count($rows) > 0)?true:false;
            }
            return $result;
        }

        public function userAuthorization(string $process, string $object):bool
        {
            $result = false;
            $sqlReader = new SqlReader();
            $process_field[SqlReader::QUERY_PLACEHOLDER] = ':PROCESS';
            $process_field[SqlReader::QUERY_VALUE] = $process;
            $process_field[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $object_field[SqlReader::QUERY_PLACEHOLDER] = ':OBJECT';
            $object_field[SqlReader::QUERY_VALUE] = $object;
            $object_field[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $id_field[SqlReader::QUERY_PLACEHOLDER] = ':ACCOUNT_ID';
            $id_field[SqlReader::QUERY_VALUE] = $this->userId;
            $id_field[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $arguments[SqlReader::QUERY_TEXT] = "
                SELECT 
                    *
                FROM 
                    business_role_rule AS brr, user_business_role AS ubr
                WHERE 
                    brr.process = :PROCESS AND brr.object = :OBJECT AND brr.business_role_id=ubr.business_role_id AND ubr.account_id=:ACCOUNT_ID
            ";
            $arguments[SqlReader::QUERY_PARAMETER] = [$process_field,$object_field,$id_field];
            $result_sql = $sqlReader ->performQuery($arguments);
            if ($result_sql[SqlReader::ERROR_INFO][0] == Common::NO_ERROR) {
                $rows = $result_sql[SqlReader::RECORDS];
                $result = (count($rows) > 0)?true:false;
            }
            return $result;
        }
    }
}