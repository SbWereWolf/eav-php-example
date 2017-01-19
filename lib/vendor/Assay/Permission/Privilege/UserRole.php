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
    use Assay\DataAccess\SqlHandler;

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
            $user_id[ISqlHandler::QUERY_PLACEHOLDER] = ':USER_ID';
            $user_id[ISqlHandler::QUERY_VALUE] = $this->userId;
            $user_id[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $role_field[ISqlHandler::QUERY_PLACEHOLDER] = ':USER_ROLE_ID';
            $role_field[ISqlHandler::QUERY_VALUE] = $role;
            $role_field[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $arguments[ISqlHandler::QUERY_TEXT] = "
                INSERT INTO 
                    ".$this->tablename."
                (
                    ".IUser::EXTERNAL_ID.", ".IUserRole::ROLE."
                ) 
                VALUES 
                    (
                        ".$user_id[ISqlHandler::QUERY_PLACEHOLDER].",".$role_field[ISqlHandler::QUERY_PLACEHOLDER]."
                    )
            ";
            $arguments[ISqlHandler::QUERY_PARAMETER] = [$user_id,$role_field];
            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);
            $result = SqlHandler::isNoError($response);

            return $result;
        }

        public function revokeRole(string $role):bool
        {
            $result = false;
            $user_id[ISqlHandler::QUERY_PLACEHOLDER] = ':USER_ID';
            $user_id[ISqlHandler::QUERY_VALUE] = $this->userId;
            $user_id[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $role_field[ISqlHandler::QUERY_PLACEHOLDER] = ':USER_ROLE_ID';
            $role_field[ISqlHandler::QUERY_VALUE] = $role;
            $role_field[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $arguments[ISqlHandler::QUERY_TEXT] = "
                DELETE FROM
                        ".$this->tablename."
                    WHERE 
                        ".IUser::EXTERNAL_ID." = ".$user_id[ISqlHandler::QUERY_PLACEHOLDER]." AND ".IUserRole::ROLE." = ".$role_field[ISqlHandler::QUERY_PLACEHOLDER]."
            ";
            $arguments[ISqlHandler::QUERY_PARAMETER] = [$user_id,$role_field];
            $sqlWriter = new SqlHandler(SqlHandler::DATA_WRITER);
            $response = $sqlWriter->performQuery($arguments);
            $result = SqlHandler::isNoError($response);

            return $result;
        }

        public function userAuthorization(string $process, string $object, string $sid):bool
        {
            $result = false;
            $process_field[ISqlHandler::QUERY_PLACEHOLDER] = ':PROCESS';
            $process_field[ISqlHandler::QUERY_VALUE] = $process;
            $process_field[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $object_field[ISqlHandler::QUERY_PLACEHOLDER] = ':OBJECT';
            $object_field[ISqlHandler::QUERY_VALUE] = $object;
            $object_field[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_STR;
            $sid_field[ISqlHandler::QUERY_PLACEHOLDER] = ':ACCOUNT_ID';
            $sid_field[ISqlHandler::QUERY_VALUE] = $this->userId;
            $sid_field[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_INT;
            $is_hidden_field[ISqlHandler::QUERY_PLACEHOLDER] = ':IS_HIDDEN';
            $is_hidden_field[ISqlHandler::QUERY_VALUE] = self::DEFAULT_IS_HIDDEN;
            $is_hidden_field[ISqlHandler::QUERY_DATA_TYPE] = \PDO::PARAM_INT;
            $arguments[ISqlHandler::QUERY_TEXT] = "
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
                    BP.".NamedEntity::CODE." = ".$process_field[ISqlHandler::QUERY_PLACEHOLDER]." AND 
                    BO.".NamedEntity::CODE." = ".$object_field[ISqlHandler::QUERY_PLACEHOLDER]." AND 
                    S.".self::ID." = ".$sid_field[ISqlHandler::QUERY_PLACEHOLDER]." AND 
                    U.".self::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::QUERY_PLACEHOLDER]." AND 
                    BO.".self::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::QUERY_PLACEHOLDER]." AND 
                    BP.".self::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::QUERY_PLACEHOLDER]." AND 
                    R.".self::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::QUERY_PLACEHOLDER]." AND
                    S.".self::IS_HIDDEN." = ".$is_hidden_field[ISqlHandler::QUERY_PLACEHOLDER]."
            ";
            var_dump("SQL",$arguments[ISqlHandler::QUERY_TEXT]);
            $arguments[ISqlHandler::QUERY_PARAMETER] = [$process_field,$object_field,$sid_field,$is_hidden_field];
            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments);
            $isSuccessfulRead = SqlHandler::isNoError($response);
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
                $result = $record != Common::EMPTY_ARRAY;
            }
            return $result;
        }
    }

}