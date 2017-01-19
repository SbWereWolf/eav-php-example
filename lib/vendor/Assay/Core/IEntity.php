<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 17:58
 */
namespace Assay\Core {
    /**
     * Базовый интерфейс сущности
     */
    interface IEntity
    {
        /** @var string колонка для айди */
        const ID = 'id';
        /** @var string колонка признака "является скрытым" */
        const IS_HIDDEN = 'is_hidden';
        
        /** @var string значение для поднятого флага "является скрытым" */
        const DEFINE_AS_HIDDEN = true;
        /** @var string значение для поднятого флага "является скрытым" */
        const DEFINE_AS_NOT_HIDDEN = false;
        /** @var string значение по умолчанию для признака "является скрытым" */
        const DEFAULT_IS_HIDDEN = self::DEFINE_AS_NOT_HIDDEN ;

        const EMPTY_VALUE = Common::EMPTY_VALUE;


        /** Добавить запись сущности в БД
         * @return bool успех выполнения
         */
        public function addEntity():bool;

        /** Скрыть сущность
         * @return bool успех выполнения
         */
        public function hideEntity():bool;

        /** Загрузить данные в соответствии с идентификатором
         * @param string $id идентификатор записи
         * @return bool успех выполнения
         */
        public function loadById(string $id):bool;

        /** Загрузить данные сохранённые в БД
         * @return bool успех выполнения
         */
        public function getStored():bool;

        /** Формирует массив из свойств экземпляра
         * @return array массив свойств экземпляра
         */
        public function toEntity():array;
        /** Обновляет (изменяет) запись в БД
         * @return bool успешность изменения
         */
        public function mutateEntity():bool;

        /** Установить свойства экземпляра в соответствии со значениями
         * @param array $namedValue массив значений
         * @return bool успех выполнения
         */
        public function setByNamedValue(array $namedValue):bool;
    }
}
