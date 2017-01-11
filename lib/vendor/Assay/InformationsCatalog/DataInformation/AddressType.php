<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:49
 */
namespace Assay\InformationsCatalog\DataInformation {

    use Assay\Core\NamedEntity;
    /**
     * Тип адреса
     */
    class AddressType extends NamedEntity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'address_type_id';

        /** @var string значение не определенно */
        const UNDEFINED = '0';
        /** @var string офис */
        const OFFICE = '1';
        /** @var string карьер */
        const MINE = '2';
        /** @var string строительная площадка*/
        const CONSTRUCTION = '3';
        /** @var string гараж */
        const GARAGE = '4';
    }
}
