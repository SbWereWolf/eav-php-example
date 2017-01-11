<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:37
 */
namespace Assay\Communication\Profile {
    interface IMessage
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'message_id';

        const CONTENT = 'content';

        public function getCorrespondent():array;

        public function getByCorrespondent():array;

        public function saveGoodsOrder():bool;

        public function saveShippingOrder():bool;
    }
}