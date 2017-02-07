<?php
/**
 * Created by PhpStorm.
 * User: Kovbii
 * Date: 30.01.2017
 * Time: 15:19
 */

namespace Assay\BusinessLogic;


interface ICommunication
{
    //получаем профиль текущего пользователя
    public function getCurrentUserProfileData($id);

    /**
     * определяем роль - пользователь или компания
     * @return string
     */
    public function getMode($id): bool;

    public function isOwnProfile($id):bool;

    public function getUserEmail($id):string;

    //создаем профиль пользователя
    public function addUserProfileData(array $values, $id):bool;

    //обновляем профиль текущего пользователя
    public function setCurrentUserProfileData(array $values, int $id):bool;
}