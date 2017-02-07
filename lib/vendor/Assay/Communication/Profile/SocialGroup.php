<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:39
 */
namespace Assay\Communication\Profile {

    use Assay\Core\NamedEntity;
    use Assay\DataAccess\ISqlHandler;
    use Assay\DataAccess\SqlHandler;

    class SocialGroup extends NamedEntity implements ISocialGroup
    {

        /** @var string имя таблицы */
        const TABLE_NAME = 'social_group';

        /** Является ли пользователь членом группы
         * добавить id пользователя ?
         * @return bool
         */
        public function isMember($profileId, $socialGroupId):bool
        {
            $result = false;
            $oneParameter[ISqlHandler::PLACEHOLDER] = ':ID';
            $oneParameter[ISqlHandler::VALUE] = $profileId;
            $oneParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $twoParameter[ISqlHandler::PLACEHOLDER] = ':ID';
            $twoParameter[ISqlHandler::VALUE] = $socialGroupId;
            $twoParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

            $arguments[ISqlHandler::QUERY_TEXT] ='
                SELECT COUNT(*) FROM '.PersonProfile::TABLE_NAME.'_'.SocialGroup::TABLE_NAME
                .' WHERE '.PersonProfile::EXTERNAL_ID.' = '. $oneParameter[ISqlHandler::PLACEHOLDER]
                .' AND '.SocialGroup::EXTERNAL_ID.' = '. $twoParameter[ISqlHandler::PLACEHOLDER]
            ;
            $arguments[ISqlHandler::QUERY_PARAMETER] = [$oneParameter, $twoParameter]; //print_r($arguments);

            $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
            $response = $sqlReader->performQuery($arguments); //print_r($response);

            if($isSuccessfulRead = SqlHandler::isNoError($response))
            {
                $record = SqlHandler::getFirstRecord($response); //print_r($record);
                if($record["count"] > 0) $result = true;
            }

            return $result;

        }
    }
}