<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:57
 */
namespace Assay\InformationsCatalog\Permission {

    use Assay\InformationsCatalog\DataInformation\InformationValue;

    class UserValue extends InformationValue
    {
        const EXTERNAL_ID = 'user_value_id';

        const USER = InformationUser::EXTERNAL_ID;
    }
}