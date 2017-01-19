<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 12:01
 */
namespace Assay\Communication\Permission {
    
    use Assay;
    use Assay\Core;
    /**
     * Запрос на общение
     */
    class CommunicationRequest implements ICommunicationRequest
    {
        /** @var string сессия пользователя */
        public $session = Core\ICommon::EMPTY_VALUE;
        /** @var string запрошенный процесс */
        public $process= Core\ICommon::EMPTY_VALUE;
        /** @var string объект для выполнения процесса */
        public $object= Core\ICommon::EMPTY_VALUE;
        /** @var string содержимое запроса */
        public $content= Core\ICommon::EMPTY_ARRAY;

        /** Проверить разрешения
         * @return bool успех проверки
         */
        public function testPrivilege():bool
        {

        }
    }
}
