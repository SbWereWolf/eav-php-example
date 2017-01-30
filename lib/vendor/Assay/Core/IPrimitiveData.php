<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.01.2017
 * Time: 1:23
 */

namespace Assay\Core;


interface IPrimitiveData
{
    /** @var string колонка признака "является скрытым" */
    const IS_HIDDEN = 'is_hidden';

    /** @var string значение для поднятого флага "является скрытым" */
    const DEFINE_AS_HIDDEN = true;
    /** @var string значение для поднятого флага "является скрытым" */
    const DEFINE_AS_NOT_HIDDEN = false;
    /** @var string значение по умолчанию для признака "является скрытым" */
    const DEFAULT_IS_HIDDEN = self::DEFINE_AS_NOT_HIDDEN ;

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

    /** Установить свойства экземпляра в соответствии со значениями
     * @param array $namedValue массив значений
     * @return bool успех выполнения
     */
    public function setByNamedValue(array $namedValue):bool;

    /** Обновляет (изменяет) запись в БД
     * @return bool успешность изменения
     */
    public function mutateEntity():bool;
}
