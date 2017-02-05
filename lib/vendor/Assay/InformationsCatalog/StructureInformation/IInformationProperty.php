<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 24.01.2017
 * Time: 15:53
 */

namespace Assay\InformationsCatalog\StructureInformation;


interface IInformationProperty
{

    /** @var string колонка для внешнего ключа ссылки на информационный домен */
    const INFORMATION_DOMAIN = InformationDomain::EXTERNAL_ID;

    /** Получить параметры поиска по рубрике
     * @return array параметры поиска
     */
    public function getSearchParameter():array;

    /** Установить информационный домен свойства рубрики
     * @param string $code код информационного домена
     * @return bool успех выполнения
     */
    public function setInformationDomain(string $code):bool;

    /** Получить тип поиска по свойству
     * @return SearchType тип поиска по свойству
     */
    public function getPropertySearchType():SearchType;

    /** Получить тип поиска по свойству
     * @return DataType тип поиска по свойству
     */
    public function getPropertyDataType():DataType;

}
