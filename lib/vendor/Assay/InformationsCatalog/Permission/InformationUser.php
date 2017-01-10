<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:56
 */
namespace Assay\InformationsCatalog\Permission {

    use Assay\Core\Entity;
    use Assay\Permission\Privilege\IUser;

    class InformationUser extends Entity
    {
        const EXTERNAL_ID = 'information_user_id';

        const USER_ID = IUser::EXTERNAL_ID;
    }
}