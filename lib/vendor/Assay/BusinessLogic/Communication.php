<?php
/**
 * Created by PhpStorm.
 * User: Kovbii
 * Date: 30.01.2017
 * Time: 15:19
 */

namespace Assay\BusinessLogic;


use Assay\Core\ICommon;
use Assay\DataAccess\ISqlHandler;
use Assay\DataAccess\SqlHandler;
use Assay\Communication\Profile\PersonProfile;
use Assay\Communication\Profile\Company;
use Assay\Communication\Profile\Common;

class Communication implements ICommunication
{
    //получаем профиль текущего пользователя
    public function getCurrentUserProfileData($id)
    {
        //$result = false;
        //if($personProfile = new PersonProfile($id)) $result = true;
        $profile = new PersonProfile($id);
        return $profile;
    }

    //проверяем, существуют ли для этого пользователя компания и объявления, если существуют, то выводим их

    public function getProfileCompany($id):array
    {
        $oneParameter[ISqlHandler::PLACEHOLDER] = ':ID';
        $oneParameter[ISqlHandler::VALUE] = $id;
        $oneParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;

        $arguments[ISqlHandler::QUERY_TEXT] =
            'SELECT company_id FROM profile_company'
            . ' WHERE profile_id '
            . ' = '
            . $oneParameter[ISqlHandler::PLACEHOLDER]
            . ' ORDER BY company_id DESC
;
';
        $arguments[ISqlHandler::QUERY_PARAMETER][] = $oneParameter;

        $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
        $response = $sqlReader->performQuery($arguments); //print_r($response);

        $isSuccessfulRead = SqlHandler::isNoError($response);


        $recordCompany = ICommon::EMPTY_ARRAY;

        $record = array();
        if ($isSuccessfulRead) {
            $record = SqlHandler::getFirstRecord($response);
            if(count($record) > 0){
                //если что-то нашли в таблице связей, ищем название компании
                $twoParameter[ISqlHandler::PLACEHOLDER] = ':COMPANY_ID';
                $twoParameter[ISqlHandler::VALUE] = $record["company_id"];
                $twoParameter[ISqlHandler::DATA_TYPE] = \PDO::PARAM_INT;
                $arguments = [];
                $arguments[ISqlHandler::QUERY_TEXT] =
                    'SELECT '.Company::ID.','.Company::NAME.' FROM '.Company::TABLE_NAME
                    . ' WHERE '.Company::ID
                    . ' = '
                    . $twoParameter[ISqlHandler::PLACEHOLDER]
                    . '  LIMIT 1
;
';
                $arguments[ISqlHandler::QUERY_PARAMETER][] = $twoParameter;

                $sqlReader = new SqlHandler(SqlHandler::DATA_READER);
                $response = $sqlReader->performQuery($arguments); //print_r($response);

                $isSuccessfulRead = SqlHandler::isNoError($response);

                $recordCompany = [];
                if ($isSuccessfulRead) {
                    $recordCompany = SqlHandler::getFirstRecord($response); //print_r($record);
                   // $company = $recordCompany;
                }
            }
            //$this->setByNamedValue($record);
        }
/*
        $result = false;
        if ($recordCompany != array()) {
            $result = true;
        }
*/

        return $recordCompany;
    }
    /**
     * определяем роль - пользователь или компания
     * @return string
     */
    public function getMode($id): bool
    {
        $this->mode = "user";
        return true;
    }

    public function isOwnProfile($id):bool
    {
        $ownProfile = 1;
        if($id != $ownProfile) return false;
        return true;
        //смотрим, какие у нашего пользователя вообще есть права, мб его вообще надо слать лесом
    }

    public function getUserEmail($id):string
    {
        //интересно, откуда здесь брать мыло, если нам в сессии дадут только id профиля?
        //в общем, что-то тут делаем и получаем мыло
        $email = 'account@email';
        return $email;
    }
    //создаем профиль пользователя
    public function addUserProfileData(array $values, $id):bool
    {
        $result = false;

        $profile = new PersonProfile($id);
        $profile->id = $id;

        $params = Common::getFieldsList('Assay\Communication\Profile\PersonProfile');

        foreach($params as $key => $value)
        {
            if($key == 'id') continue;
            $keyCamel =  Common::camelCase($key, [], PersonProfile::WORD_DIVIDER);
            $k = 'Assay\Communication\Profile\PersonProfile::'.$value;
            $v = constant($k);
            if(isset($values[$key]))
                $profile->{$keyCamel} = $values[$key];
            else $profile->{$keyCamel} = PersonProfile::EMPTY_VALUE;//Common::setIfExists($v, $values[$key], self::EMPTY_VALUE);
            // print_r($key);
        }

        $profile->addEntity();
        $result = $profile->mutateEntity();

        return $result;
    }
    //обновляем профиль текущего пользователя
    public function setCurrentUserProfileData(array $values, int $id):bool
    {

        $profile = new PersonProfile($id);
        $profile->id = $id;

        $result = false;
        $params = Common::getFieldsList('Assay\Communication\Profile\PersonProfile');

        foreach($params as $key => $value)
        {
            //print_r($key);
            if($key == 'id') continue; //чтоб не было возможности менять чужой профиль
            $keyCamel = Common::camelCase($key, [], PersonProfile::WORD_DIVIDER);
            $k = 'Assay\Communication\Profile\PersonProfile::'.$value;
            $v = constant($k);
            if(isset($values[$key]) && !empty($values[$key]))
                $profile->{$keyCamel} = $values[$key];
            else {
                if($profile->fieldTypes[$key] == 'PARAM_INT')  $profile->{$keyCamel} = PersonProfile::EMPTY_VALUE;
                else $profile->{$keyCamel} = PersonProfile::EMPTY_VALUE;

            }//self::EMPTY_VALUE;//Common::setIfExists($v, $values[$key], self::EMPTY_VALUE);
            // print_r($key);
        }

        if($profile->mutateEntity() && $profile->loadById($profile->id))
            $result = true;
        //           print_r($result);
        return $result;

        // if($this->loadById($profileId)) $result = true;




        // return $this->loadById($profileId);
    }

    //----------------------------------Сообщения---------------------------------------------------------------------
}