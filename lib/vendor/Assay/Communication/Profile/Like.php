<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:40
 */
namespace Assay\Communication\Profile {
    class Like extends SocialElement
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'like_id';
    }
}