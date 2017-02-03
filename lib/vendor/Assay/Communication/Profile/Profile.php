<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:42
 */
namespace Assay\Communication\Profile {

    use Assay\Core;
    use \Assay\Core\Common;
    use \Assay\DataAccess\ISqlHandler;
    use \Assay\DataAccess\SqlHandler;
    use Assay\Permission\Privilege;

    class Profile extends \Assay\Core\NamedEntity implements IProfile
    {

/*

        //получаем список полей таблицы
        public function getFieldsList():array
        {
            $refl = new \ReflectionClass('Assay\Communication\Profile\Profile');
            $params = $refl->getConstants();
            $strangeParams = [];
            foreach ($params as $key => $value)
            {
                if(is_array($value)) continue;
                $stringKey = (string) $key;
                $stringValue = (string) $value;
                $strangeParams[$stringValue] = $stringKey;
            }

            unset($strangeParams[self::TABLE_NAME]);
            unset($strangeParams[self::EMPTY_VALUE]);
            unset($strangeParams[self::EMPTY_OBJECT]);
            unset($strangeParams[self::DEFINE_AS_HIDDEN]);
            unset($strangeParams[self::DEFINE_AS_NOT_HIDDEN]);
            unset($strangeParams[self::DEFAULT_IS_HIDDEN]);
            unset($strangeParams[self::WORD_DIVIDER]);
            unset($strangeParams[self::GREETINGS_ROLE]);
            unset($strangeParams[self::EXTERNAL_ID]);
            //print_r($strangeParams);

            return $strangeParams;
        }
        */


        // public $profile; //- это id

        /** для капчи
         * @return bool
         */
        public function getCommentEnableArea():bool
        {
           // return $this->isOwnProfile();
            return false;
        }

        /** Имя для приветствия - показывается в форме отображения Профиля. И, наверное, в приветствии при заходе.
         * задается пользователем в настройках.
         * @return string
         */
        public function getGreetingsRole(): string
        {
            $result = Common::EMPTY_VALUE;
            return $result;
        }

        /*
            public function readEntity(string $id):bool
        {
            $id_field[ISqlHandler::PLACEHOLDER] = ':ID';
            $id_field[ISqlHandler::VALUE] = $id;
            $id_field[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;
            $is_hidden[ISqlHandler::PLACEHOLDER] = ':IS_HIDDEN';
            $is_hidden[ISqlHandler::VALUE] = self::DEFAULT_IS_HIDDEN;
            $is_hidden[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] ='
                SELECT 
                    *
                FROM 
                    '.$this->tablename.'
                WHERE 
                    '.self::IS_HIDDEN.'='.$is_hidden[ISqlHandler::PLACEHOLDER].' AND 
                    '.self::ID.'='.$id_field[ISqlHandler::PLACEHOLDER].'
            ';
            $arguments[ISqlHandler::QUERY_PARAMETER] = [$is_hidden,$id_field];
            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments); //print_r($response); exit;
            $isSuccessfulRead = SqlHandler::isNoError($response);

            $record = array();
            if ($isSuccessfulRead) {
                $record = SqlHandler::getFirstRecord($response);
                $this->setByNamedValue($record);
            }

            $result = $record != Common::EMPTY_ARRAY;

            return $result;
        }
        */

        }
}