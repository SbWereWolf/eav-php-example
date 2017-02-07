<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:12
 */
namespace Assay\InformationsCatalog\StructureInformation {

    use Assay\Core\ICommon;

    /**
     * Функционал для работы с доменом свойства
     */
    interface IInformationDomain
    {
        /** @var string колонка для ссылки на тип редактирования */
        const TYPE_EDIT = TypeEdit::EXTERNAL_ID;
        /** @var string колонка для ссылки на тип поиска */
        const SEARCH_TYPE = SearchType::EXTERNAL_ID;
        /** @var string колонка для ссылки на тип данных */
        const DATA_TYPE = DataType::EXTERNAL_ID;

    }
}
