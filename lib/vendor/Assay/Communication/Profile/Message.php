<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:41
 */
namespace Assay\Communication\Profile {
    class Message extends SocialElement implements IMessage
    {
        const GOODS_ORDER_PATTERN = 'GOODS_ORDER_PATTERN';
        const SHIPPING_ORDER_PATTERN = 'SHIPPING_ORDER_PATTERN';

        public $correspondent;

        public function getCorrespondent():array
        {
        }

        public function getByCorrespondent():array
        {
        }

        public function saveGoodsOrder():bool
        {
        }

        public function saveShippingOrder():bool
        {
        }
    }
}