<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.01.2017
 * Time: 17:32
 */

namespace Assay\Core;


class Record
{
    /** @var string константа значение не задано для значимых типов */
    const EMPTY_VALUE = ICommon::EMPTY_VALUE;

    /** @var string колонка для идентификатора */
    const ID = 'id';

    /** @var string имя таблицы БД для хранения записи */
    const TABLE_NAME = 'record_table';
    /** @var string имя таблицы БД для хранения записи */
    protected $tablename = self::TABLE_NAME;

    /** @var string идентификатор записи */
    public $id = self::EMPTY_VALUE;
}
