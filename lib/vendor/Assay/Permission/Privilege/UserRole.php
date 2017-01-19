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
    use Assay\Core\NamedEntity;
    use Assay\DataAccess\SqlReader;

    class UserRole extends Entity implements IUserRole, IAuthorizeProcess
    {

        /** @var string ссылка на учётную запись */
        public $userId;
        public $userRole;

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

        public function userAuthorization(string $process, string $object, string $sid):bool
        {
            $result = false;
            $sqlReader = new SqlReader();
            $process_field[SqlReader::QUERY_PLACEHOLDER] = ':PROCESS';
            $process_field[SqlReader::QUERY_VALUE] = $process;
            $process_field[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $object_field[SqlReader::QUERY_PLACEHOLDER] = ':OBJECT';
            $object_field[SqlReader::QUERY_VALUE] = $object;
            $object_field[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $sid_field[SqlReader::QUERY_PLACEHOLDER] = ':ACCOUNT_ID';
            $sid_field[SqlReader::QUERY_VALUE] = $this->userId;
            $sid_field[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_INT;
            $is_hidden_field[SqlReader::QUERY_PLACEHOLDER] = ':IS_HIDDEN';
            $is_hidden_field[SqlReader::QUERY_VALUE] = self::DEFAULT_IS_HIDDEN;
            $is_hidden_field[SqlReader::QUERY_DATA_TYPE] = \PDO::PARAM_INT;
            /*
            $arguments[SqlReader::QUERY_TEXT] = "
                SELECT 
                    NULL
                FROM
                    ".BusinessProcess::TABLE_NAME." BP
                JOIN 
                    ".ObjectPrivilege::TABLE_NAME." P ON BP.".self::ID." = P.".BusinessProcess::EXTERNAL_ID."
                JOIN 
                    ".BusinessObject::TABLE_NAME." BO ON BO.".self::ID." = P.".BusinessObject::EXTERNAL_ID."
                JOIN 
                    ".RoleDetail::TABLE_NAME." RD ON P.".self::ID." = RD.".ObjectPrivilege::EXTERNAL_ID."
                JOIN 
                    ".BusinessRole::TABLE_NAME." R ON RD.".BusinessRole::EXTERNAL_ID." = R.".self::ID."
                JOIN 
                    ".self::TABLE_NAME." AR ON R.".self::ID." = AR.".BusinessRole::EXTERNAL_ID."
                JOIN 
                    ".User::TABLE_NAME." U ON U.".self::ID." = AR.".User::EXTERNAL_ID."
                JOIN 
                    ".Session::TABLE_NAME." S ON S.".User::EXTERNAL_ID." = U.".self::ID."
                WHERE
                    BP.".NamedEntity::CODE." = ".$process_field[SqlReader::QUERY_PLACEHOLDER]." AND 
                    BO.".NamedEntity::CODE." = ".$object_field[SqlReader::QUERY_PLACEHOLDER]." AND 
                    S.".self::ID." = ".$sid_field[SqlReader::QUERY_PLACEHOLDER]." AND 
                    U.".self::IS_HIDDEN." = ".$is_hidden_field[SqlReader::QUERY_PLACEHOLDER]." AND 
                    BO.".self::IS_HIDDEN." = ".$is_hidden_field[SqlReader::QUERY_PLACEHOLDER]." AND 
                    BP.".self::IS_HIDDEN." = ".$is_hidden_field[SqlReader::QUERY_PLACEHOLDER]." AND 
                    R.".self::IS_HIDDEN." = ".$is_hidden_field[SqlReader::QUERY_PLACEHOLDER]." AND
                    S.".self::IS_HIDDEN." = ".$is_hidden_field[SqlReader::QUERY_PLACEHOLDER]."
            ";
            */
            $arguments[SqlReader::QUERY_TEXT] = "
                SELECT 
                    NULL 
                FROM 
                    ".Session::TABLE_NAME." S 
                JOIN 
                    ".User::TABLE_NAME." U ON S.".User::EXTERNAL_ID." = U.".self::ID."
                JOIN 
                    ".self::TABLE_NAME." UR ON U.".self::ID." = UR.".User::EXTERNAL_ID."
                JOIN 
                    ".BusinessRole::TABLE_NAME." R ON R.".self::ID." = UR.".BusinessRole::EXTERNAL_ID."
                JOIN 
                    ".RoleDetail::TABLE_NAME." RD ON RD.".BusinessRole::EXTERNAL_ID." = R.".self::ID." 
                JOIN 
                    ".ObjectPrivilege::TABLE_NAME." P ON P.".self::ID." = RD.".ObjectPrivilege::EXTERNAL_ID."
                JOIN 
                    ".BusinessProcess::TABLE_NAME." BP ON BP.".self::ID." = P.".BusinessProcess::EXTERNAL_ID."
                JOIN 
                    ".BusinessObject::TABLE_NAME." BO ON BO.".self::ID." = P.".BusinessObject::EXTERNAL_ID."
                WHERE
                    BP.".NamedEntity::CODE." = ".$process_field[SqlReader::QUERY_PLACEHOLDER]." AND 
                    BO.".NamedEntity::CODE." = ".$object_field[SqlReader::QUERY_PLACEHOLDER]." AND 
                    S.".self::ID." = ".$sid_field[SqlReader::QUERY_PLACEHOLDER]." AND 
                    U.".self::IS_HIDDEN." = ".$is_hidden_field[SqlReader::QUERY_PLACEHOLDER]." AND 
                    BO.".self::IS_HIDDEN." = ".$is_hidden_field[SqlReader::QUERY_PLACEHOLDER]." AND 
                    BP.".self::IS_HIDDEN." = ".$is_hidden_field[SqlReader::QUERY_PLACEHOLDER]." AND 
                    R.".self::IS_HIDDEN." = ".$is_hidden_field[SqlReader::QUERY_PLACEHOLDER]." AND
                    S.".self::IS_HIDDEN." = ".$is_hidden_field[SqlReader::QUERY_PLACEHOLDER]."
            ";
            var_dump("SQL",$arguments[SqlReader::QUERY_TEXT]);
            $arguments[SqlReader::QUERY_PARAMETER] = [$process_field,$object_field,$sid_field,$is_hidden_field];
            $result_sql = $sqlReader ->performQuery($arguments);
            if ($result_sql[SqlReader::ERROR_INFO][0] == Common::NO_ERROR) {
                $rows = $result_sql[SqlReader::RECORDS];
                $result = (count($rows) > 0)?true:false;
            }
            return $result;
        }
    }

}