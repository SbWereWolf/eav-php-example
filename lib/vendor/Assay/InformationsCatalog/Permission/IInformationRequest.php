<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:57
 */
namespace Assay\InformationsCatalog\Permission {
    /**
     * Функционал информационного запроса
     */
    interface IInformationRequest
    {
        /** Проверить разрешения
         * @return bool успех проверки
         */
        public function testPrivilege():bool;
    }
}
