<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 16.01.2017
 * Time: 12:33
 */

namespace Assay\Core;


interface IHide
{
    /** @var string колонка признака "является скрытым" */
    const IS_HIDDEN = 'is_hidden';
    /** @var string колонка дата добавления */
    //const INSERT_DATE = 'insert_date';
    /** @var string колонка дата обновления */
    const ACTIVITY_DATE = 'activity_date';
    /** @var string значение для поднятого флага "является скрытым" */
    const DEFINE_AS_HIDDEN = true;
    /** @var string значение для поднятого флага "является скрытым" */
    const DEFINE_AS_NOT_HIDDEN = false;
    /** @var string значение по умолчанию для признака "является скрытым" */
    const DEFAULT_IS_HIDDEN = self::DEFINE_AS_NOT_HIDDEN ;
    
    /** Скрыть сущность
     * @return bool успех операции
     */
    public function hideEntity():bool;
}
