<?php
/**
 * Created by PhpStorm.
 * User: Sancho
 * Date: 11.01.2017
 * Time: 10:07
 */
namespace Assay\Permission\Privilege {
    interface IProcessRequest
    {
        /** @var string режим пользователь */
        const MODE_USER = 'mode_user';
        /** @var string режим компания */
        const MODE_COMPANY = 'mode_company';
        /** @var string режим оператор */
        const MODE_OPERATOR = 'mode_operator';
        /** @var string режим редактор */
        const MODE_REDACTOR = 'mode_redactor';
        /** @var string режим администратор */
        const MODE_ADMINISTRATOR = 'mode_administrator';
        /** @var string добавить отзыв */
        const ADD_REVIEW = 'add_review';

        /** @var string изменение разрешений для пользователей */
        const EDIT_PERMISSION = 'edit_permission';

        /** @var string регистрация */
        const USER_REGISTRATION = 'user_registration';
        /** @var string изменить профиль */
        const CHANGE_PROFILE = 'user_profile_edit';
        /** @var string просмотр профиля* /
         * const VIEW_PROFILE = 'user_profile_view';
         * /** @var string вход
         */
        const USER_LOGON = 'user_logon';
        // начать сеанс
        // выход
        /** @var string восстановить пароль */
        const PASSWORD_RESET = 'password_reset';
        /** @var string изменить пароль */
        const CHANGE_PASSWORD = 'edit_password';

        /** @var string поиск в каталоге */
        const CATALOG_SEARCH = 'catalog_search';
        /** @var string просмотр структуры */
        const STRUCTURE_VIEW = 'structure_view';
        /** @var string добавление и удаление элементов структуры */
        const STRUCTURE_ADD = 'structure_add';
        /** @var string изменение пользовательских свойств для элементов структуры */
        const STRUCTURE_EDIT_USER_DATA = 'structure_edit_user_data';
        /** @var string изменение системных свойств элементов структуры */
        const STRUCTURE_EDIT_SYSTEM_DATA = 'structure_edit_system_data';
        /** @var string поиск в рубрике */
        const RUBRIC_SEARCH = 'rubric_search';
        /** @var string просмотр данных */
        const RUBRIC_VIEW = 'rubric_view';
        /** @var string добавление и удаление элементов рубрики */
        const RUBRIC_ADD_COMMON = 'rubric_add_common';
        /** @var string добавление и удаление соотнесённых элементов рубрики */
        const RUBRIC_ADD_SELF = 'rubric_add_self';
        /** @var string изменение пользовательских свойств для элементов рубрики; */
        const RUBRIC_EDIT_USER_DATA = 'rubric_edit_user_data';
        /** @var string изменение системных свойств элементов структуры */
        const RUBRIC_EDIT_SYSTEM_DATA = 'rubric_edit_system_data';
        /** @var string изменение пользовательских свойств Компании; */
        const RUBRIC_EDIT_SELF_DATA = 'rubric_edit_self_data';
        /** @var string вычислить формулу */
        const CALCULATE_FORMULA = 'calculate_formula';

        /** @var string добавить заявку на товар */
        const ADD_ORDER_GOODS = 'add_order_goods';
        /** @var string добавить заявку на доставку */
        const ADD_ORDER_SHIPPING = 'add_order_shipping';
        /** @var string просмотр комментариев */
        const COMMENT_VIEW = 'comment_view';
        /** @var string добавить комментарий */
        const COMMENT_ADD = 'comment_add';
        /** @var string добавить сообщение */
        const MESSAGE_ADD = 'message_add';
        /** @var string просматривать сообщения */
        const MESSAGE_VIEW = 'message_view';
        /** @var string добавить в избранное */
        const ADD_FAVORITE = 'add_favorite';
        /** @var string добавить лайк */
        const ADD_LIKE = 'add_like';
        /** @var string добавить объявление */
        const ADD_AD = 'add_ad';

        /** Проверить привелегию на выполнение процесса
         * @return bool успех проверки
         */
        public function testPrivilege():bool;
    }
}