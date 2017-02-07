<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:09
 */
namespace Assay\Permission\Privilege {

    use Assay\Core\InnerLinkageEntity;

    class BusinessObjectPrivilege extends InnerLinkageEntity
    {
        /** @var string название таблицы */
        const TABLE_NAME = 'business_object_business_process';
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'business_object_business_process_id';

        /** @var string колонка ссылки на бизнес объект */
        const LEFT = BusinessObject::EXTERNAL_ID;
        /** @var string колонка ссылки на бизнес процесс */
        const RIGHT = BusinessProcess::EXTERNAL_ID;

        /** @var string имя таблицы для хранения сущности */
        protected $tablename = self::TABLE_NAME;
        /** @var string имя левой таблицы */
        protected $leftColumn = self::LEFT;
        /** @var string имя правой таблицы */
        protected $rightColumn = self::RIGHT;

        /** @var string бизнес процесс */
        public $leftId = self::EMPTY_VALUE;
        /** @var string бизнес объект */
        public $rightId = self::EMPTY_VALUE;
    }
}