<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:47
 */
namespace Assay\InformationsCatalog\DataInformation {
    
    /**
     * Вычисление формулы
     */
    interface IInformationInstance
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'information_instance_id';
        
        /** @var string колонка для ссылки на рубрику */
        const RUBRIC = 'rubric_id';

        /** Получить позицию для отображения
         * @param array $type типы отображения
         * @return array результат вычисления
         */
        public function getPositionByPrivileges($type = array()):array;

        /** Выполнить поиск
         * @param array $filterProperties параметры поиска
         * @param int $start показать позиции результата начиная с номера
         * @param int $paging количество для отображения
         * @return array результаты поиска
         */
        public function search(array $filterProperties, int $start, int $paging):array;
    }
}
