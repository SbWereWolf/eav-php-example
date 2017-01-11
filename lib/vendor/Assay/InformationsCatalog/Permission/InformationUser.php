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
    /**
     * Пользователь информационного каталога
     */
    class InformationUser extends Entity
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'information_user_id';

        /** @var string колонка для ссылки на учётную запись */
        const USER_ID = IUser::EXTERNAL_ID;
    }
}
