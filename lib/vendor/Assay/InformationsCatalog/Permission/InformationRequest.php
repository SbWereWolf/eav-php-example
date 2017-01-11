<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:58
 */
namespace Assay\InformationsCatalog\Permission {
    /**
     * Функционал информационного запроса
     */
    class InformationRequest implements IInformationRequest
    {
        /** @var string сессия */
        public $session;
        /** @var string процесс */
        public $process;
        /** @var string объект */
        public $object;
        /** @var string содержание процесса */
        public $content;
        /** Проверить привелегию на выполнение процесса
         * @return bool успех проверки
         */
        public function testPrivilege():bool
        {

        }
    }
}
