<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 11:56
 */
namespace Assay\Communication\Permission {
    /**
     * Интерфейс обработки запросов на общение
     */
    interface ICommunicationRequest
    {
        /** Проверить разрешения
         * @return bool успех проверки
         */
        public function testPrivilege():bool;
    }
}
