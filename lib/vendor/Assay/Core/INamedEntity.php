<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:03
 */
namespace Assay\Core {
    /**
     * Интерфейс для работы с именнуемыми сущностями
     */
    interface INamedEntity
    {
        /** @var string колонка КОД */
        const CODE = 'code';
        /** @var string колонка НАИМЕНОВАНИЕ */
        const NAME = 'name';
        /** @var string колонка ОПИСАЕИЕ */
        const DESCRIPTION = 'description';

        /** Чтение записи из БД по коду
         * @param string $code код записи
         * @return array значения записи
         */
        public function loadByCode(string $code):array;

        /** Получить имя и описание записи
         * @return array массив с именем и описанием
         */
        public function getElementDescription():array;

    }
}
