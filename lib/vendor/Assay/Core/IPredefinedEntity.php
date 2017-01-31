<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.01.2017
 * Time: 1:17
 */

namespace Assay\Core;


interface IPredefinedEntity
{

    /** Добавить дочернюю сущность
     * @return bool успех выполнения
     */
    public function addPredefinedEntity():bool;

}
