<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.01.2017
 * Time: 23:28
 */

namespace Assay\ModuleConnection;


use Assay\Communication\InformationsCatalog\CommunicationObject;
use Assay\Communication\Profile\PersonProfile;

class PersonalProfileObject
{
    /** @var string колонка внешнего ключа для ссылки на эту таблицу */
    const EXTERNAL_ID = 'personal_profile_object_id';

    /** @var string колонка внешнего ключа для ссылки на социальную группу */
    const PERSON_PROFILE = PersonProfile::EXTERNAL_ID;
    /** @var string колонка внешнего ключа для ссылки на объект общения */
    const COMMUNICATION = CommunicationObject::EXTERNAL_ID;

    /** @var string назнвание таблицы для хранения данных сущности */
    const TABLE_NAME = 'personal_profile_object';

    /** @var string назнвание таблицы для хранения данных сущности */
    protected $tablename = self::TABLE_NAME;
}
