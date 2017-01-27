<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.01.2017
 * Time: 1:17
 */

namespace Assay\Core;


interface IChildEntity
{

    /** Добавить дочернюю сущность
     * @return bool успех выполнения
     */
    public function addChildEntity():bool;

}
