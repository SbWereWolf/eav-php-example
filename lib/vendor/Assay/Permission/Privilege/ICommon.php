<?php
/**
 * Created by PhpStorm.
 * User: sancho
 * Date: 06.02.17
 * Time: 13:47
 */

namespace Assay\Permission\Privilege;

interface ICommon
{
    /** @var string колонка дата активности */
    const ACTIVITY_DATE = 'activity_date';
    /** @var string колонка дата обновления */
    const UPDATE_DATE = 'update_date';
}