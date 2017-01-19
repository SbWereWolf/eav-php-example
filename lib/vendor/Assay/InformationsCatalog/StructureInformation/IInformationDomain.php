<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:12
 */
namespace Assay\InformationsCatalog\StructureInformation {
    /**
     * Функционал для работы с доменом свойства
     */
    interface IInformationDomain
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'information_domain_id';

        /** @var string колонка для ссылки на тип редактирования */
        const TYPE_EDIT = TypeEdit::EXTERNAL_ID;
        /** @var string колонка для ссылки на тип поиска */
        const SEARCH_TYPE = SearchType::EXTERNAL_ID;

        /** Получить параметры фильтра этого свойства
         * @return array параметры фильтра
         */
        public function getSearchParameters():array;

    }
}
