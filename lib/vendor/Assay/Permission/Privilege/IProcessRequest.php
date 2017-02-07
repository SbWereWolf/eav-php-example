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
        const PROCESS_MODE_USER = 'mode_user';
        /** @var string режим компания */
        const PROCESS_MODE_COMPANY = 'mode_company';
        /** @var string режим оператор */
        const PROCESS_MODE_OPERATOR = 'mode_operator';
        /** @var string режим редактор */
        const PROCESS_MODE_REDACTOR = 'mode_redactor';
        /** @var string режим администратор */
        const PROCESS_MODE_ADMINISTRATOR = 'mode_administrator';
        /** @var string добавить отзыв */
        const PROCESS_ADD_REVIEW = 'add_review';

        /** @var string изменение разрешений для пользователей */
        const PROCESS_EDIT_PERMISSION = 'edit_permission';

        /** @var string регистрация */
        const PROCESS_USER_REGISTRATION = 'user_registration';
        /** @var string изменить профиль */
        const PROCESS_CHANGE_PROFILE = 'user_profile_edit';
        /** @var string просмотр профиля* */
        const VIEW_PROFILE = 'user_profile_view';
        /* * /** @var string вход
         */
        const PROCESS_USER_LOGON = 'user_logon';
        // начать сеанс
        // выход
        const PROCESS_USER_LOGOUT = 'user_logout';
        /** @var string восстановить пароль */
        const PROCESS_PASSWORD_RESET = 'password_reset';
        /** @var string изменить пароль */
        const PROCESS_CHANGE_PASSWORD = 'edit_password';

        /** @var string поиск в каталоге */
        const PROCESS_CATALOG_SEARCH = 'catalog_search';
        /** @var string просмотр структуры */
        const PROCESS_STRUCTURE_VIEW = 'structure_view';
        /** @var string добавление и удаление элементов структуры */
        const PROCESS_STRUCTURE_ADD = 'structure_add';
        /** @var string изменение пользовательских свойств для элементов структуры */
        const PROCESS_STRUCTURE_EDIT_USER_DATA = 'structure_edit_user_data';
        /** @var string изменение системных свойств элементов структуры */
        const PROCESS_STRUCTURE_EDIT_SYSTEM_DATA = 'structure_edit_system_data';
        /** @var string поиск в рубрике */
        const PROCESS_RUBRIC_SEARCH = 'rubric_search';
        /** @var string просмотр данных */
        const PROCESS_RUBRIC_VIEW = 'rubric_view';
        /** @var string добавление и удаление элементов рубрики */
        const PROCESS_RUBRIC_ADD_COMMON = 'rubric_add_common';
        /** @var string добавление и удаление соотнесённых элементов рубрики */
        const PROCESS_RUBRIC_ADD_SELF = 'rubric_add_self';
        /** @var string изменение пользовательских свойств для элементов рубрики; */
        const PROCESS_RUBRIC_EDIT_USER_DATA = 'rubric_edit_user_data';
        /** @var string изменение системных свойств элементов структуры */
        const PROCESS_RUBRIC_EDIT_SYSTEM_DATA = 'rubric_edit_system_data';
        /** @var string изменение пользовательских свойств Компании; */
        const PROCESS_RUBRIC_EDIT_SELF_DATA = 'rubric_edit_self_data';
        /** @var string вычислить формулу */
        const PROCESS_CALCULATE_FORMULA = 'calculate_formula';

        /** @var string добавить заявку на товар */
        const PROCESS_ADD_ORDER_GOODS = 'add_order_goods';
        /** @var string добавить заявку на доставку */
        const PROCESS_ADD_ORDER_SHIPPING = 'add_order_shipping';
        /** @var string просмотр комментариев */
        const PROCESS_COMMENT_VIEW = 'comment_view';
        /** @var string добавить комментарий */
        const PROCESS_COMMENT_ADD = 'comment_add';
        /** @var string добавить сообщение */
        const PROCESS_MESSAGE_ADD = 'message_add';
        /** @var string просматривать сообщения */
        const PROCESS_MESSAGE_VIEW = 'message_view';
        /** @var string добавить в избранное */
        const PROCESS_ADD_FAVORITE = 'add_favorite';
        /** @var string добавить лайк */
        const PROCESS_ADD_LIKE = 'add_like';
        /** @var string добавить объявление */
        const PROCESS_ADD_AD = 'add_ad';

        //Объекты
        /** @var string аккаунт */
        const OBJECT_ACCOUNT = 'account';
        /** @var string аккаунт */
        const OBJECT_REVIEW = 'review';
        /** @var string каталог */
        const OBJECT_CATALOG = 'catalog';
        /** @var string формула */
        const OBJECT_FORMULA = 'formula';
        /** @var string товары */
        const OBJECT_GOODS = 'goods';
        /** @var string комментарии */
        const OBJECT_COMMENT = 'comment';
        /** @var string сообщения */
        const OBJECT_MESSAGE = 'message';
        /** @var string избранное */
        const OBJECT_FAVORITE = 'favorite';
        /** @var string лайки */
        const OBJECT_LIKE = 'like';
        /** @var string объявления */
        const OBJECT_AD = 'ad';

        //Режимы
        /** @var string объявления */
        const MODE_GUEST = 'guest';
        /** @var string объявления */
        const MODE_USER = 'user';
        /** @var string объявления */
        const MODE_COMPANY = 'company';
        /** @var string объявления */
        const MODE_OPERATOR = 'operator';
        /** @var string объявления */
        const MODE_REDACTOR = 'redactor';
        /** @var string объявления */
        const MODE_ADMINISTRATOR = 'administrator';

        /** Проверить привелегию на выполнение процесса
         * @return bool успех проверки
         */
        public function testPrivilege():bool;
    }
}