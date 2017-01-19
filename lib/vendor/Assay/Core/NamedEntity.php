<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:03
 */
namespace Assay\Core {
    /**
     * Реализация интерфейса для работы с именнуемыми сущностями
     */
    class NamedEntity extends MutableEntity implements INamedEntity
    {
        /** @var string код */
        public $code;
        /** @var string имя */
        public $name;
        /** @var string описание */
        public $description;
        /** Чтение записи из БД по коду
         * @param string $code код записи
         * @return array значения записи
         */
        public function loadByCode(string $code):array
        {
        }
        /** Получить имя и описание записи
         * @return array массив с именем и описанием
         */
        public function getElementDescription():array
        {
        }
    }
}
