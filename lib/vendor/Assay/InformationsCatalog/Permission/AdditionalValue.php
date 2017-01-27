<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:57
 */
namespace Assay\InformationsCatalog\Permission {

    use Assay\InformationsCatalog\DataInformation\InformationValue;
    /**
     * Пользовательские данные
     */
    class AdditionalValue extends InformationValue
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'additional_value_id';

        /** @var string колонка для ссылки на пользователя информационного каталога */
        const USER = Redactor::EXTERNAL_ID;
    }
}
