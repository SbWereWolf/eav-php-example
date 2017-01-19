<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:36
 */
namespace Assay\Communication\Profile {
    interface IComment
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'comment_id';

        const CONTENT = 'content';

        public function getByObject():array;
    }
}