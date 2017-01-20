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
    class NamedEntity extends Entity implements INamedEntity
    {
        /** @var string код */
        public $code;
        /** @var string имя */
        public $name;
        /** @var string описание */
        public $description;

        /** Загрузить по коду записи
         * @param string $code код записи
         * @return bool успех выполнения
         */
        public function loadByCode(string $code):bool
        {
            $result = false;
            return $result;
        }

        /** Получить имя и описание записи
         * @param string $code значение ключа для свойства код
         * @param string $name значение ключа для свойства имя
         * @param string $description значение ключа для свойства описание
         * @return array массив с именем и описанием
         */
        public function getElementDescription(string $code = INamedEntity::CODE,
                                              string $name = INamedEntity::NAME,
                                              string $description = INamedEntity::DESCRIPTION):array
        {

        }
    }
}
