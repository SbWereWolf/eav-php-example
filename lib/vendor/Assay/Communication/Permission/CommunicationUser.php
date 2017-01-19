<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 11:56
 */
namespace Assay\Communication\Permission {

    use Assay\Core\Entity;
    use Assay\Permission\Privilege\IUser;
    /**
     * Пользователь общения
     */
    class CommunicationUser extends Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'communication_user_id';

        /** @var string колонка ссылки на учётную запись пользователя */
        const USER_ID = IUser::EXTERNAL_ID;
    }
}
