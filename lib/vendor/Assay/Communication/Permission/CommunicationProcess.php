<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 11:56
 */
namespace Assay\Communication\Permission {

    use Assay\Core\NamedEntity;
    /**
     * Процесс общения
     */
    class CommunicationProcess extends NamedEntity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'communication_process_id';
    }
}
