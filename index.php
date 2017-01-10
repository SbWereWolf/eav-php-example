<?php

namespace Assay\Permission\Privilege {

    use Assay;
    use Assay\Core;

    interface IUser
    {
        const EXTERNAL_ID = 'user_id';

        const LOGIN = 'login';
        const PASSWORD_HASH = 'password_hash';
        const ACTIVITY_DATE = 'activity_date';
        const EMAIL = 'email';

        const EMPTY_VALUE = Core\Common::EMPTY_VALUE;
        const DEFAULT_ALGORITHM = PASSWORD_BCRYPT ;
        
        public function registration(string $login, string $password, string $passwordConfirmation, string $email):bool;

        public function changePassword(string $newPassword):bool;

        public function recoveryPassword():bool;

        public function updateActivityDate():bool;

        public function getStored():array;

        public function setByDefault():bool;

        public function loadByEmail(string $email):bool;

        public function sendRecovery():bool;
    }

    interface IAuthenticateUser
    {
        public function authentication(string $password):bool;

        public static function calculateHash(string $password):string;
    }

    class User extends Assay\Core\MutableEntity implements IUser, IAuthenticateUser
    {
        const TABLE_NAME = 'authentic_user';

        public $login;
        public $passwordHash;
        public $activityDate;
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

        public function changePassword(string $newPassword):bool
        {
            $this->passwordHash=self::calculateHash($newPassword);
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
            $result =  password_verify($password,$this->passwordHash);

            return $result;
        }

        public static function calculateHash(string $password, int $algorithm=self::DEFAULT_ALGORITHM):string
        {
            $result = password_hash($password,$algorithm);
            return $result;
        }

        public function setByDefault():bool
        {
            // SET TO GUEST USER ;
        }

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
        const EXTERNAL_ID = 'user_object_id';
    }

    class BusinessProcess extends Assay\Core\NamedEntity
    {
        const EXTERNAL_ID = 'user_process_id';
    }

    class ObjectPrivilege extends Assay\Core\Entity
    {
        const EXTERNAL_ID = 'object_privilege_id';

        const BUSINESS_OBJECT = BusinessObject::EXTERNAL_ID;
        const BUSINESS_PROCESS = BusinessProcess::EXTERNAL_ID;

        public $businessProcess;
        public $businessObject;
    }

    class BusinessRole extends Assay\Core\NamedEntity
    {
        const EXTERNAL_ID = 'business_role_id';
    }

    class RoleDetail extends Assay\Core\NamedEntity
    {
        const EXTERNAL_ID = 'role_detail_id';

        const PRIVILEGE = ObjectPrivilege::EXTERNAL_ID;
        const ROLE = BusinessRole::EXTERNAL_ID;

        public $privilege;
        public $role;
    }

    interface IUserRole
    {
        const EXTERNAL_ID = 'user_role_id';

        const USER = IUser::EXTERNAL_ID;
        const ROLE = BusinessRole::EXTERNAL_ID;

        public function grantRole(string $role):bool;

        public function revokeRole(string $role):bool;

    }

    interface IAuthorizeProcess
    {
        public function userAuthorization(string $process, string $object):bool;
    }

    class UserRole extends Assay\Core\Entity implements IUserRole, IAuthorizeProcess
    {
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

    interface IProcessRequest
    {
        const MODE_USER = 'mode_user'; // режим пользователь
        const MODE_COMPANY = 'mode_company'; // режим компания
        const MODE_OPERATOR = 'mode_operator'; // режим оператор
        const MODE_REDACTOR = 'mode_redactor'; // режим редактор
        const MODE_ADMINISTRATOR = 'mode_administrator'; // режим администратор
        const ADD_REVIEW = 'add_review'; // добавить отзыв

        const EDIT_PERMISSION = 'edit_permission'; // изменение разрешений для пользователей

        const USER_REGISTRATION = 'user_registration'; // регистрация
        const CHANGE_PROFILE = 'user_profile_edit'; // изменить профиль
        const VIEW_PROFILE = 'user_profile_view'; // просмотр профиля
        const USER_LOGON = 'user_logon'; // вход
        // начать сеанс
        // выход
        const PASSWORD_RESET = 'password_reset'; // восстановить пароль
        const CHANGE_PASSWORD = 'edit_password'; // изменить пароль

        const CATALOG_SEARCH = 'catalog_search'; // поиск в каталоге
        const STRUCTURE_VIEW = 'structure_view'; // просмотр структуры
        const STRUCTURE_ADD = 'structure_add'; // добавление и удаление элементов структуры 
        const STRUCTURE_EDIT_USER_DATA = 'structure_edit_user_data'; // изменение пользовательских свойств для элементов структуры
        const STRUCTURE_EDIT_SYSTEM_DATA = 'structure_edit_system_data'; // изменение системных свойств элементов структуры
        const RUBRIC_SEARCH = 'rubric_search'; // поиск в рубрике
        const RUBRIC_VIEW = 'rubric_view'; // просмотр данных
        const RUBRIC_ADD_COMMON = 'rubric_add_common'; // добавление и удаление элементов рубрики
        const RUBRIC_ADD_SELF = 'rubric_add_self'; // добавление и удаление соотнесённых элементов рубрики
        const RUBRIC_EDIT_USER_DATA = 'rubric_edit_user_data'; // изменение пользовательских свойств для элементов рубрики;
        const RUBRIC_EDIT_SYSTEM_DATA = 'rubric_edit_system_data'; // изменение системных свойств элементов структуры
        const RUBRIC_EDIT_SELF_DATA = 'rubric_edit_self_data'; // изменение пользовательских свойств Компании;
        const CALCULATE_FORMULA = 'calculate_formula'; // вычислить формулу

        const ADD_ORDER_GOODS = 'add_order_goods'; // добавить заявку на товар
        const ADD_ORDER_SHIPPING = 'add_order_shipping'; // добавить заявку на доставку
        const COMMENT_VIEW = 'comment_view'; // просмотр комментариев
        const COMMENT_ADD = 'comment_add'; // добавить комментарий
        const MESSAGE_ADD = 'message_add'; // добавить сообщение
        const MESSAGE_VIEW = 'message_view'; // просматривать сообщения
        const ADD_FAVORITE = 'add_favorite'; // добавить в избранное
        const ADD_LIKE = 'add_like'; // добавить лайк
        const ADD_AD = 'add_ad'; // добавить объявление

        public function testPrivilege():bool;
    }

    class ProcessRequest implements IProcessRequest
    {
        public $session;
        public $process;
        public $object;
        public $content;

        public function testPrivilege():bool
        {

        }
    }

    interface ICookies
    {
        const KEY = 'key';
        const COMPANY_FILTER = 'company_filter';
        const MODE = 'mode';
        const PAGING = 'paging';
        const USER_NAME = 'user_name';

        const EMPTY_VALUE = Assay\Core\Common::EMPTY_VALUE;

        public function setKey():bool;

        public static function getKey():string;

        public function setCompanyFilter():bool;

        public static function getCompanyFilter():string;

        public function setMode():bool;

        public static function getMode():string;

        public function setPaging():bool;

        public static function getPaging():string;

        public function setUserName():bool;

        public static function getUserName():string;

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

        public function setKey():bool
        {
        }

        public static function getKey():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public function setCompanyFilter():bool
        {
        }

        public static function getCompanyFilter():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public function setMode():bool
        {
        }

        public static function getMode():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public function setPaging():bool
        {
        }

        public static function getPaging():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public function setUserName():bool
        {
        }

        public static function getUserName():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }
    }

    interface ISession
    {
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

        public function setByCookie(Cookie $cookies)
        {
            $this->cookies = $cookies;

            $this->key = $this->cookies->key;
            $this->companyFilter = $this->cookies->companyFilter;
            $this->mode = $this->cookies->mode;
            $this->paging = $this->cookies->paging;
            $this->userName = $this->cookies->userName;
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

        public function getStored():array
        {
            $result = array();
            return $result;
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

        public static function open(string $userId):array
        {
            $process = self::OPEN_PROCESS;
            $object = self::SESSION_OBJECT;
            $result = array();

            $userRole = new UserRole($userId);
            $isAllow = $userRole->userAuthorization($process, $object);
            if($isAllow){
                $result[self::USER_ID]=$userId;
                $key = uniqid('',true);
                $result[self::KEY]=$key;

                $session = new Session();
                $id = $session->addEntity();
                $result[self::ID] = $id;

                $session->setByNamedValue($result);
                $session->mutateEntity();
            }
            return $result;
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

    class SocialObject extends Assay\Core\Entity
    {
        const EXTERNAL_ID = 'social_object_id';

        public $object;
    }

    interface IPersonProfile
    {
        const EXTERNAL_ID = 'person_profile_id';

        const OBJECT = SocialObject::EXTERNAL_ID;

        public function getForGreetings():string;

        public function enableCommenting():bool;

        public function testPrivilege():bool;

        public function purgeGroup():bool;

        public function setGroup():bool;
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

    interface ISocialGroup
    {
        const EXTERNAL_ID = 'social_group_id';

        const OBJECT = SocialObject::EXTERNAL_ID;

        public function isMember():bool;
    }

    class SocialGroup extends Assay\Core\NamedEntity implements ISocialGroup
    {
        public function isMember():bool
        {
        }
    }

    class GroupMembership extends Assay\Core\Entity
    {
        const EXTERNAL_ID = 'group_membership_id';

        const GROUP = SocialGroup::EXTERNAL_ID;
        const PROFILE = PersonProfile::EXTERNAL_ID;
    }

    class ProfileFeature extends Assay\Core\ReadableEntity
    {
        const PROFILE = Assay\Communication\Profile\IPersonProfile::EXTERNAL_ID;

        public $profile;
    }

    interface ISocialElement
    {
        const SOCIAL_OBJECT = SocialObject::EXTERNAL_ID;

        public function count():int;

        public function isOwn():bool;
    }

    class SocialElement extends ProfileFeature implements ISocialElement
    {
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
        const EXTERNAL_ID = 'like_id';
    }

    class Favorite extends SocialElement
    {
        const EXTERNAL_ID = 'favorite_id';
    }

    interface IComment
    {
        const EXTERNAL_ID = 'comment_id';

        const CONTENT = 'content';

        public function getByObject():array;
    }

    class Comment extends SocialElement implements IComment
    {
        public function getByObject():array
        {
        }
    }

    interface IMessage
    {
        const EXTERNAL_ID = 'message_id';

        const CONTENT = 'content';

        public function getCorrespondent():array;

        public function getByCorrespondent():array;

        public function saveGoodsOrder():bool;

        public function saveShippingOrder():bool;
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
        const PROFILE = Communication\Profile\IPersonProfile::EXTERNAL_ID;

        public $profile;
    }

    interface IAd
    {

        const EXTERNAL_ID = 'ad_id';

        const SOCIAL_OBJECT = SocialObject::EXTERNAL_ID;

        const CONTENT = 'content';
        const UPDATE_DATE = 'update_date';

        public function purge():bool;
    }

    class Ad extends ProfileFeature implements IAd
    {

        public $content;
        public $social_object;

        public function purge():bool
        {
        }
    }

    interface ISocialBlock
    {
        public function getCounter():array;

        public function getComment():array;
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

    interface ISocialAction
    {
        public function socialAction():bool;
    }

    class SocialAction implements ISocialAction
    {
        public function socialAction():bool
        {
        }
    }

    interface IProfile
    {

        public function getCommentEnableArea():bool;
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

    class CommunicationUser extends Assay\Core\Entity
    {
        const EXTERNAL_ID = 'communication_user_id';

        const USER_ID = Assay\Permission\Privilege\IUser::EXTERNAL_ID;
    }

    class CommunicationProcess extends Assay\Core\NamedEntity
    {
        const EXTERNAL_ID = 'communication_process_id';
    }

    class SocialGroupObject extends Assay\Core\Entity
    {
        const EXTERNAL_ID = 'social_group_object_id';

        const SOCIAL_GROUP = Communication\Profile\SocialGroup::EXTERNAL_ID;
    }

    class CommunicationPrivilege extends Assay\Core\Entity
    {
        const EXTERNAL_ID = 'communication_privilege_id';

        const ROLE_DETAIL = Privilege\RoleDetail::EXTERNAL_ID;
    }

    class SocialGroupPrivilege extends Assay\Core\Entity
    {
        const EXTERNAL_ID = 'social_group_privilege_id';

        const PROCESS = CommunicationProcess::EXTERNAL_ID;
        const OBJECT = SocialGroupObject::EXTERNAL_ID;
        const COMMUNICATION_PRIVILEGE = CommunicationPrivilege::EXTERNAL_ID;
    }

    interface ICommunicationRequest
    {
        public function testPrivilege():bool;
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
        const EXTERNAL_ID = 'communication_object_id';
    }

    class CommunicationSocialObject extends Assay\Core\Entity
    {
        const EXTERNAL_ID = 'communication_social_object_id';

        const SOCIAL_OBJECT = Assay\Communication\Profile\SocialObject::EXTERNAL_ID;
        const COMMUNICATION_OBJECT = CommunicationObject::EXTERNAL_ID;
    }

    class InformationsCatalogObject extends Assay\Core\Entity
    {
        const EXTERNAL_ID = 'information_catalog_object_id';
    }

    class InstanceObject extends Assay\Core\Entity // +Structure +Rubric
    {
        const EXTERNAL_ID = 'instance_object_id';

        const INSTANCE = InformationsCatalog\DataInformation\IInformationInstance::EXTERNAL_ID;
        const INFORMATION_OBJECT = InformationsCatalogObject::EXTERNAL_ID;
    }

    class CommunicationInformationLinking extends Assay\Core\Entity
    {
        const EXTERNAL_ID = 'communication_information_object_id';

        const COMMUNICATION = CommunicationSocialObject::EXTERNAL_ID;
        const INFORMATION = InformationsCatalogObject::EXTERNAL_ID;
    }
}
