<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:09
 */
namespace Assay\Permission\Privilege {

    use Assay\Core\InnerLinkageEntity;

    class BusinessRolePrivilege extends InnerLinkageEntity
    {
        /** @var string название таблицы */
        const TABLE_NAME = 'role_detail';
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'role_detail_id';

        /** @var string колонка ссылки на объект привелегий  */
        const LEFT = BusinessObjectPrivilege::EXTERNAL_ID;
        /** @var string колонка ссылки на бизнес роль */
        const RIGHT = BusinessRole::EXTERNAL_ID;

        /** @var string имя таблицы для хранения сущности */
        protected $tablename = self::TABLE_NAME;
        /** @var string имя левой таблицы */
        protected $leftColumn = self::LEFT;
        /** @var string имя правой таблицы */
        protected $rightColumn = self::RIGHT;

        /** @var string привелегия */
        public $leftId = self::EMPTY_VALUE;
        /** @var string роль */
        public $rightId = self::EMPTY_VALUE;
    }
}