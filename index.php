<?php

namespace Assay\Permission\Privilege {

    use Assay;
    use Assay\Core;

    /**
     * Интерфейс для работы с учётной записью
     */
    interface IUser
    {
        /** @var string колонка для внешнего ключа ссылки на эту таблицу */
        const EXTERNAL_ID = 'user_id';

        /** @var string колонка "Имя учётной записи" */
        const LOGIN = 'login';
        /** @var string колонка хэш пароля */
        const PASSWORD_HASH = 'password_hash';
        /** @var string колонка дата последней активности */
        const ACTIVITY_DATE = 'activity_date';
        /** @var string колонка электронная почта */
        const EMAIL = 'email';

        /** @var string константа "значение не определено" */
        const EMPTY_VALUE = Core\Common::EMPTY_VALUE;
        /** @var string алгоритм по умолчанию для расчёта хэша */
        const DEFAULT_ALGORITHM = PASSWORD_BCRYPT;

        /** Регистрация учётной записи
         * @param string $login имя учётной записи
         * @param string $password пароль
         * @param string $passwordConfirmation подтвержение пароля
         * @param string $email электронная почты
         * @return bool успех выполнения
         */
        public function registration(string $login, string $password, string $passwordConfirmation, string $email):bool;

        /** Сохранение нового пароля учётной записи
         * @param string $newPassword новый пароль
         * @return bool успех выполнения
         */
        public function changePassword(string $newPassword):bool;

        /** Сброс пароля
         * @return bool успех выполнения
         */
        public function recoveryPassword():bool;

        /** Обновить дату активности учётной записи
         * @return bool успех выполнения
         */
        public function updateActivityDate():bool;

        /** Получить запись из БД
         * @return array значения колонок
         */
        public function getStored():array;

        /** Установить по умолчанию
         * @return bool успех выполнения
         */
        public function setByDefault():bool;

        /** Загрузить учётную запись по электронной почте
         * @param string $email
         * @return bool успех выполнения
         */
        public function loadByEmail(string $email):bool;

        /** Отправить ссылку для сброса пароля
         * @return bool успех выполнения
         */
        public function sendRecovery():bool;
    }

    interface IAuthenticateUser
    {
        /** Расчитать хэш
         * @param string $password пароль
         * @return string хэш пароля
         */
        public static function calculateHash(string $password):string;

        /** Аутентифицировать пользователя
         * @param string $password пароль
         * @return bool успех аутентификации
         */
        public function authentication(string $password):bool;
    }

    interface IUserRole
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'user_role_id';

        /** @var string колонка ссылки на учётную запись пользователя */
        const USER = IUser::EXTERNAL_ID;
        /** @var string колонка ссылки на бизнес роль */
        const ROLE = BusinessRole::EXTERNAL_ID;

        /** Назначить роль
         * @param string $role роль
         * @return bool успех выполнения
         */
        public function grantRole(string $role):bool;

        /** снять роль
         * @param string $role роль
         * @return bool успех выполнения
         */
        public function revokeRole(string $role):bool;

    }

    interface IAuthorizeProcess
    {
        /** Авторизация пользователя для выполнения процесса
         * @param string $process процесс
         * @param string $object объект
         * @return bool успех авторизации
         */
        public function userAuthorization(string $process, string $object):bool;
    }

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

    interface ICookies
    {
        /** @var string номер сессии */
        const KEY = 'key';
        /** @var string фильтр по компании */
        const COMPANY_FILTER = 'company_filter';
        /** @var string режим */
        const MODE = 'mode';
        /** @var string пагинация */
        const PAGING = 'paging';
        /** @var string имя пользователя */
        const USER_NAME = 'user_name';

        /** @var string константа для пустого значения */
        const EMPTY_VALUE = Assay\Core\Common::EMPTY_VALUE;

        /** Получить номер сесии
         * @return string номер сессии
         */
        public static function getKey():string;

        /** Получить фильтр по компании
         * @return string фильт по компании
         */
        public static function getCompanyFilter():string;

        /** Получить режим
         * @return string режим
         */
        public static function getMode():string;

        /** Получить пагинацию
         * @return string пагинация
         */
        public static function getPaging():string;

        /** Получить имя пользователя
         * @return string
         */
        public static function getUserName():string;

        /** Записать номер сессии
         * @return bool успех выполнения
         */
        public function setKey():bool;

        /** Записать фильтр по компании
         * @return bool успех выполнения
         */
        public function setCompanyFilter():bool;

        /** Записать режим
         * @return bool успех выполнения
         */
        public function setMode():bool;

        /** Записать пагинацию
         * @return bool успех выполнения
         */
        public function setPaging():bool;

        /** Записать имя пользователя
         * @return bool успех выполнения
         */
        public function setUserName():bool;

    }

    interface ISession
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'session_id';
        const EMPTY_VALUE = Assay\Core\Common::EMPTY_VALUE;

        const KEY = ICookies::KEY;
        const COMPANY_FILTER = ICookies::COMPANY_FILTER;
        const MODE = ICookies::MODE;
        const PAGING = ICookies::PAGING;
        const USER_NAME = ICookies::USER_NAME;

        const USER_ID = 'user_id';

        const OPEN_PROCESS = 'open_session';
        const SESSION_OBJECT = 'session';

        public static function open(string $userId):array;

        public function close():bool;
    }

    class User extends Assay\Core\MutableEntity implements IUser, IAuthenticateUser
    {
        /** @var string имя таблицы */
        const TABLE_NAME = 'authentic_user';

        /** @var string имя учётной записи */
        public $login;
        /** @var string хэш пароля */
        public $passwordHash;
        /** @var string дата последней активности */
        public $activityDate;
        /** @var string электронная почта */
        public $email;

        public function registration(string $login, string $password, string $passwordConfirmation, string $email):bool
        {
            $result = false;

            $isCorrectPassword = $password == $passwordConfirmation;
            if ($isCorrectPassword) {
                $this->activityDate = time();
                $this->email = $email;
                $this->isHidden = Core\IEntity::DEFAULT_IS_HIDDEN;
                $this->login = $login;
                $this->passwordHash = self::calculateHash($password);

                $this->id = $this->addEntity();
                $result = $this->mutateEntity();
            }

            return $result;
        }

        public static function calculateHash(string $password, int $algorithm = self::DEFAULT_ALGORITHM):string
        {
            $result = password_hash($password, $algorithm);
            return $result;
        }

        /** Обновляет (изменяет) запись в БД
         * @return bool успешность изменения
         */
        public function mutateEntity():bool
        {
            $storedData = $this->getStored();
            $entity = $this->toEntity();

            $needUpdate = false;
            foreach ($entity as $key => $column) {
                $isExist = array_key_exists($key, $storedData);
                $equal = true;
                if ($isExist) {
                    $equal = $column == $storedData[$key];
                }
                if (!$equal) {
                    $needUpdate = true;
                }
            }
            if ($needUpdate) {
                // UPDATE DB RECORD;
            }

            $result = true;
            return $result;

        }

        /** Формирует массив из свойств экземпляра
         * @return array массив свойств экземпляра
         */
        public function toEntity():array
        {
            $entity = [];
            $entity[self::ID] = $this->id;
            $entity[self::IS_HIDDEN] = $this->isHidden;
            $entity[self::LOGIN] = $this->login;
            $entity[self::PASSWORD_HASH] = $this->passwordHash;
            $entity[self::ACTIVITY_DATE] = $this->activityDate;
            $entity[self::EMAIL] = $this->email;
            return $entity;
        }

        public function changePassword(string $newPassword):bool
        {
            $this->passwordHash = self::calculateHash($newPassword);
            $result = $this->mutateEntity();
            return $result;
        }

        public function recoveryPassword():bool
        {
            $result = true;
            // SEND EMAIL WITH RECOVERY LINK;
            return $result;
        }

        public function updateActivityDate():bool
        {
            $this->activityDate = time();
            $result = $this->mutateEntity();

            return $result;
        }

        public function authentication(string $password):bool
        {
            $result = password_verify($password, $this->passwordHash);

            return $result;
        }

        public function setByDefault():bool
        {
            // SET TO GUEST USER ;
        }

        /** Установить свойства объекта в соответствии с массивом
         * @param array $namedValue массив значений
         */
        public function setByNamedValue(array $namedValue)
        {
            $this->id = Core\Common::setIfExists(self::ID, $namedValue, self::EMPTY_VALUE);
            $this->insertDate = Core\Common::setIfExists(self::INSERT_DATE, $namedValue, self::EMPTY_VALUE);
            $this->isHidden = Core\Common::setIfExists(self::IS_HIDDEN, $namedValue, self::EMPTY_VALUE);
            $this->login = Core\Common::setIfExists(self::LOGIN, $namedValue, self::EMPTY_VALUE);
            $this->passwordHash = Core\Common::setIfExists(self::PASSWORD_HASH, $namedValue, self::EMPTY_VALUE);
            $this->activityDate = Core\Common::setIfExists(self::ACTIVITY_DATE, $namedValue, self::EMPTY_VALUE);
            $this->email = Core\Common::setIfExists(self::EMAIL, $namedValue, self::EMPTY_VALUE);
        }

        public function loadByEmail(string $email):bool
        {
            $result = true;
            return $result;
        }

        public function sendRecovery():bool
        {
            $result = true;
            return $result;
        }
    }

    class BusinessObject extends Assay\Core\NamedEntity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'user_object_id';
    }

    class BusinessProcess extends Assay\Core\NamedEntity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'user_process_id';
    }

    class ObjectPrivilege extends Assay\Core\Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'object_privilege_id';

        /** @var string колонка ссылки на бизнес объект */
        const BUSINESS_OBJECT = BusinessObject::EXTERNAL_ID;
        /** @var string колонка ссылки на бизнес процесс */
        const BUSINESS_PROCESS = BusinessProcess::EXTERNAL_ID;

        /** @var string бизнес процесс */
        public $businessProcess;
        /** @var string бизнес объект */
        public $businessObject;
    }

    class BusinessRole extends Assay\Core\NamedEntity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'business_role_id';
    }

    class RoleDetail extends Assay\Core\NamedEntity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'role_detail_id';

        /** @var string колонка ссылки на объект привелегий  */
        const PRIVILEGE = ObjectPrivilege::EXTERNAL_ID;
        /** @var string колонка ссылки на бизнес роль */
        const ROLE = BusinessRole::EXTERNAL_ID;

        /** @var string привелегия */
        public $privilege;
        /** @var string роль */
        public $role;
    }

    class UserRole extends Assay\Core\Entity implements IUserRole, IAuthorizeProcess
    {
        /** @var string ссылка на учётную запись */
        public $userId;

        public function __construct(string $userId)
        {
            $this->userId = $userId;
        }

        public function grantRole(string $role):bool
        {
        }

        public function revokeRole(string $role):bool
        {
        }

        public function userAuthorization(string $process, string $object):bool
        {
            $result = false;
            return $result;
        }
    }

    class ProcessRequest implements IProcessRequest
    {
        /** @var string сессия */
        public $session;
        /** @var string процесс */
        public $process;
        /** @var string объект */
        public $object;
        /** @var string содержание процесса */
        public $content;

        public function testPrivilege():bool
        {

        }
    }

    class Cookie implements ICookies
    {
        public $key;
        public $companyFilter;
        public $mode;
        public $paging;
        public $userName;

        public function __construct()
        {
            $this->key = self::getKey();
            $this->companyFilter = self::getCompanyFilter();
            $this->mode = self::getMode();
            $this->paging = self::getPaging();
            $this->userName = self::getUserName();
        }

        public static function getKey():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public static function getCompanyFilter():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public static function getMode():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public static function getPaging():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public static function getUserName():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public function setKey():bool
        {
        }

        public function setCompanyFilter():bool
        {
        }

        public function setMode():bool
        {
        }

        public function setPaging():bool
        {
        }

        public function setUserName():bool
        {
        }
    }

    class Session extends Assay\Core\MutableEntity implements ISession
    {

        public $cookies;

        public $key;
        public $companyFilter;
        public $mode;
        public $paging;
        public $userName;

        public $userId;

        public function __construct()
        {
            $this->cookies = Assay\Core\Common::EMPTY_OBJECT;

            $this->key = ISession::EMPTY_VALUE;
            $this->companyFilter = ISession::EMPTY_VALUE;
            $this->mode = ISession::EMPTY_VALUE;
            $this->paging = ISession::EMPTY_VALUE;
            $this->userName = ISession::EMPTY_VALUE;

            $this->userId = ISession::EMPTY_VALUE;
        }

        public static function open(string $userId):array
        {
            $process = self::OPEN_PROCESS;
            $object = self::SESSION_OBJECT;
            $result = array();

            $userRole = new UserRole($userId);
            $isAllow = $userRole->userAuthorization($process, $object);
            if ($isAllow) {
                $result[self::USER_ID] = $userId;
                $key = uniqid('', true);
                $result[self::KEY] = $key;

                $session = new Session();
                $id = $session->addEntity();
                $result[self::ID] = $id;

                $session->setByNamedValue($result);
                $session->mutateEntity();
            }
            return $result;
        }

        public function setByNamedValue(array $namedValues)
        {
            $this->key = Assay\Core\Common::setIfExists(
                self::KEY, $namedValues, ISession::EMPTY_VALUE
            );
            $this->companyFilter = Assay\Core\Common::setIfExists(
                self::COMPANY_FILTER, $namedValues, ISession::EMPTY_VALUE
            );
            $this->mode = Assay\Core\Common::setIfExists(
                self::MODE, $namedValues, ISession::EMPTY_VALUE
            );
            $this->paging = Assay\Core\Common::setIfExists(
                self::PAGING, $namedValues, ISession::EMPTY_VALUE
            );
            $this->userName = Assay\Core\Common::setIfExists(
                self::USER_NAME, $namedValues, ISession::EMPTY_VALUE
            );
            $this->userId = Assay\Core\Common::setIfExists(
                self::USER_ID, $namedValues, ISession::EMPTY_VALUE
            );
        }

        public function mutateEntity():bool
        {
            $storedData = $this->getStored();
            $entity = $this->toEntity();

            $needUpdate = false;
            foreach ($entity as $key => $column) {
                $isExist = array_key_exists($key, $storedData);
                $equal = true;
                if ($isExist) {
                    $equal = $column == $storedData[$key];
                }
                if (!$equal) {
                    $needUpdate = true;
                }
            }
            if ($needUpdate) {
                // UPDATE DB RECORD;
            }

            $result = true;
            return $result;

        }

        public function getStored():array
        {
            $result = array();
            return $result;
        }

        public function toEntity():array
        {
            $entity = [];
            $entity[self::ID] = $this->id;
            $entity[self::IS_HIDDEN] = $this->isHidden;
            $entity[self::KEY] = $this->key;
            $entity[self::COMPANY_FILTER] = $this->companyFilter;
            $entity[self::MODE] = $this->mode;
            $entity[self::PAGING] = $this->paging;
            $entity[self::USER_NAME] = $this->userName;
            $entity[self::USER_ID] = $this->userId;

            return $entity;
        }

        public function setByCookie(Cookie $cookies)
        {
            $this->cookies = $cookies;

            $this->key = $this->cookies->key;
            $this->companyFilter = $this->cookies->companyFilter;
            $this->mode = $this->cookies->mode;
            $this->paging = $this->cookies->paging;
            $this->userName = $this->cookies->userName;
        }

        public function close():bool
        {
            $result = $this->hideEntity();
            return $result;
        }
    }
}

namespace Assay\Communication\Profile {

    use Assay;
    use Assay\Core;
    use Assay\Communication;

    interface IPersonProfile
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'person_profile_id';

        /** @var string колонка ссылки на социальный объект */
        const OBJECT = SocialObject::EXTERNAL_ID;

        public function getForGreetings():string;

        public function enableCommenting():bool;

        public function testPrivilege():bool;

        public function purgeGroup():bool;

        public function setGroup():bool;
    }

    interface ISocialGroup
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'social_group_id';

        /** @var string колонка ссылки на социальный объект */
        const OBJECT = SocialObject::EXTERNAL_ID;

        public function isMember():bool;
    }

    interface ISocialElement
    {
        /** @var string колонка ссылки на социальный объект */
        const SOCIAL_OBJECT = SocialObject::EXTERNAL_ID;

        public function count():int;

        public function isOwn():bool;
    }

    interface IComment
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'comment_id';

        const CONTENT = 'content';

        public function getByObject():array;
    }

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

    interface IAd
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'ad_id';

        /** @var string колонка ссылки на социальный объект */
        const SOCIAL_OBJECT = SocialObject::EXTERNAL_ID;

        const CONTENT = 'content';
        const UPDATE_DATE = 'update_date';

        public function purge():bool;
    }

    interface ISocialBlock
    {
        public function getCounter():array;

        public function getComment():array;
    }

    interface ISocialAction
    {
        public function socialAction():bool;
    }

    interface IProfile
    {

        public function getCommentEnableArea():bool;
    }

    class SocialObject extends Assay\Core\Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'social_object_id';

        public $object;
    }

    class PersonProfile extends Assay\Core\NamedEntity implements IPersonProfile
    {
        public function getForGreetings():string
        {
        }

        public function enableCommenting():bool
        {
        }

        public function testPrivilege():bool
        {
        }

        public function purgeGroup():bool
        {
        }

        public function setGroup():bool
        {
        }
    }

    class SocialGroup extends Assay\Core\NamedEntity implements ISocialGroup
    {
        public function isMember():bool
        {
        }
    }

    class GroupMembership extends Assay\Core\Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'group_membership_id';

        /** @var string колонка ссылки на социальную группу */
        const GROUP = SocialGroup::EXTERNAL_ID;
        /** @var string колонка ссылки на профиль пользователя */
        const PROFILE = PersonProfile::EXTERNAL_ID;
    }

    class ProfileFeature extends Assay\Core\ReadableEntity
    {
        /** @var string колонка ссылки на профиль пользователя */
        const PROFILE = Assay\Communication\Profile\IPersonProfile::EXTERNAL_ID;

        /** @var string профилоь пользователя */
        public $profile;
    }

    class SocialElement extends ProfileFeature implements ISocialElement
    {
        /** @var string социальный объект */
        public $object;

        public function count():int
        {
        }

        public function isOwn():bool
        {
        }
    }

    class Like extends SocialElement
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'like_id';
    }

    class Favorite extends SocialElement
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'favorite_id';
    }

    class Comment extends SocialElement implements IComment
    {
        public function getByObject():array
        {
        }
    }

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

    class CommunicationFeature extends Assay\Core\MutableEntity
    {
        /** @var string колонка ссылки на профиль пользователя */
        const PROFILE = Communication\Profile\IPersonProfile::EXTERNAL_ID;

        public $profile;
    }

    class Ad extends ProfileFeature implements IAd
    {

        public $content;
        public $social_object;

        public function purge():bool
        {
        }
    }

    class SocialBlock implements ISocialBlock
    {

        public function getCounter():array
        {
        }

        public function getComment():array
        {
        }
    }

    class SocialAction implements ISocialAction
    {
        public function socialAction():bool
        {
        }
    }

    class Profile implements IProfile
    {

        public $profile;

        public function getCommentEnableArea():bool
        {
        }
    }
}
namespace Assay\Communication\Permission {

    use Assay;
    use Assay\Core;
    use Assay\Permission;
    use Assay\Permission\Privilege;
    use Assay\Communication;
    use Assay\Communication\Profile;

    interface ICommunicationRequest
    {
        public function testPrivilege():bool;
    }

    class CommunicationUser extends Assay\Core\Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'communication_user_id';

        /** @var string колонка ссылки на учётную запись пользователя */
        const USER_ID = Assay\Permission\Privilege\IUser::EXTERNAL_ID;
    }

    class CommunicationProcess extends Assay\Core\NamedEntity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'communication_process_id';
    }

    class SocialGroupObject extends Assay\Core\Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'social_group_object_id';

        const SOCIAL_GROUP = Communication\Profile\SocialGroup::EXTERNAL_ID;
    }

    class CommunicationPrivilege extends Assay\Core\Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'communication_privilege_id';

        /** @var string колонка ссылки на привелегию роли */
        const ROLE_DETAIL = Privilege\RoleDetail::EXTERNAL_ID;
    }

    class SocialGroupPrivilege extends Assay\Core\Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'social_group_privilege_id';

        /** @var string колонка ссылки на социальный процесс */
        const PROCESS = CommunicationProcess::EXTERNAL_ID;
        /** @var string колонка ссылки на социальную группу */
        const OBJECT = SocialGroupObject::EXTERNAL_ID;
        /** @var string колонка ссылки на социальную привелегию */
        const COMMUNICATION_PRIVILEGE = CommunicationPrivilege::EXTERNAL_ID;
    }

    class CommunicationRequest implements ICommunicationRequest
    {
        public $session;
        public $process;
        public $object;
        public $content;

        public function testPrivilege():bool
        {

        }
    }
}
namespace Assay\Communication\InformationsCatalog {

    use Assay;
    use Assay\Core;
    use Assay\InformationsCatalog;
    use Assay\InformationsCatalog\DataInformation;
    use Assay\InformationsCatalog\Permission;
    use Assay\Communication;
    use Assay\Communication\Profile;

    class CommunicationObject extends Assay\Core\Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'communication_object_id';
    }

    class CommunicationSocialObject extends Assay\Core\Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'communication_social_object_id';

        /** @var string колонка ссылки на социальный объект */
        const SOCIAL_OBJECT = Assay\Communication\Profile\SocialObject::EXTERNAL_ID;
        /** @var string колонка ссылки на объект общения */
        const COMMUNICATION_OBJECT = CommunicationObject::EXTERNAL_ID;
    }

    class InformationsCatalogObject extends Assay\Core\Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'information_catalog_object_id';
    }

    class InstanceObject extends Assay\Core\Entity // +Structure +Rubric
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'instance_object_id';

        /** @var string колонка ссылки на воплощение рубрики */
        const INSTANCE = InformationsCatalog\DataInformation\IInformationInstance::EXTERNAL_ID;
        /** @var string колонка ссылки на объект каталога */
        const INFORMATION_OBJECT = InformationsCatalogObject::EXTERNAL_ID;
    }

    class CommunicationInformationLinking extends Assay\Core\Entity
    {
        /** @var string колонка внешнего ключа для ссылки на эту таблицу */
        const EXTERNAL_ID = 'communication_information_object_id';

        /** @var string колонка ссылки на социальный объект общения */
        const COMMUNICATION = CommunicationSocialObject::EXTERNAL_ID;
        /** @var string колонка ссылки на объект информационного каталога */
        const INFORMATION = InformationsCatalogObject::EXTERNAL_ID;
    }
}
