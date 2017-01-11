<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 10.01.2017
 * Time: 18:47
 */
namespace Assay\InformationsCatalog\DataInformation {

    use Assay\Core\NamedEntity;
    use Assay\InformationsCatalog\StructureInformation\TypeEdit;
    /**
     * Позиция рубрики
     */
    class InformationInstance extends NamedEntity implements IInformationInstance, IInstanceUserInformation
    {
        /** @var string ссылка на рубрику */
        public $rubricId;

        /** Получить свойства цены доставки
         * @return array свойства цены
         */
        public function getShippingPricing():array
        {
        }
        /** Получить свойства цены товара
         * @return array свойства цены
         */
        public function getGoodsPricing():array
        {
        }
        /** Получить позицию для отображения
         * @param array $type типы отображения
         * @return array результат вычисления
         */
        public function getPositionByPrivileges($type = array()):array
        {
        }
        /** Выполнить поиск
         * @param array $filterProperties параметры поиска
         * @param int $start показать позиции результата начиная с номера
         * @param int $paging количество для отображения
         * @return array результаты поиска
         */
        public function search(array $filterProperties, int $start, int $paging):array
        {
        }

    }
}
