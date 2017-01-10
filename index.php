<?php
namespace Assay\Core {

    interface ICommon
    {
        public static function isSetEx($valueIfIsset, $valueIfNotIsset);

        public static function setIfExists($key, &$array, $valueIfNotIsset);
    }

    interface IEntity
    {

        const ID = 'id';
        const IS_HIDDEN = 'is_hidden';
        const INSERT_DATE = 'insert_date';

        const DEFAULT_IS_HIDDEN = false;

        public function addEntity():int;

        public function hideEntity():bool;
    }

    interface IReadableEntity
    {
        // прочитать данные по айдишнику
        public function readEntity(string $id):array;

        // прочитать данные экземпляра из БД
        /**
         * @return array
         */
        public function getStored():array;

        // задать свойства экземпляра по именованным значениям
        public function setByNamedValue(array $namedValue);
    }

    interface IMutableEntity
    {
        public function mutateEntity():bool;

        public function toEntity():array;

    }

    interface INamedEntity
    {
        const CODE = 'code';
        const NAME = 'name';
        const DESCRIPTION = 'description';

        public function loadByCode(string $code):array;

        public function getElementDescription():array;

    }

    class Common implements ICommon
    {
        const EMPTY_VALUE = '';
        const EMPTY_OBJECT = null;

        public static function setIfExists($key, &$array, $valueIfNotIsset)
        {
            $value = $valueIfNotIsset;
            $maySet = array_key_exists($key, $array);
            if ($maySet) {
                $value = self::isSetEx($array[$key], $valueIfNotIsset);
            }
            return $value;
        }

        public static function isSetEx($valueIfIsset, $valueIfNotIsset)
        {
            $value = isset($valueIfIsset) ? $valueIfIsset : $valueIfNotIsset;
            return $value;
        }
    }

    class Entity implements IEntity
    {

        const TABLE_NAME = 'entity_table';

        public $id;
        public $isHidden;
        public $insertDate;

        public function addEntity():int
        {
            $result = 0;
            return $result;
        }

        public function hideEntity():bool
        {
        }
    }

    class ReadableEntity extends Entity implements IReadableEntity
    {

        public function readEntity(string $id):array
        {
        }

        public function getStored():array
        {
            $result = array();
            return $result;
        }

        public function setByNamedValue(array $namedValue)
        {
        }
    }

    class MutableEntity extends Entity implements IMutableEntity, IReadableEntity
    {
        public function mutateEntity():bool
        {

        }

        public function readEntity(string $id):array
        {
            $result = array();
            return $result;
        }

        public function getStored():array
        {
            $result = array();
            return $result;
        }

        public function setByNamedValue(array $namedValue)
        {
        }

        public function toEntity():array
        {
            $result = array();
            return $result;
        }

    }

    class NamedEntity extends MutableEntity implements INamedEntity
    {
        public $code;
        public $name;
        public $description;

        public function loadByCode(string $code):array
        {
        }

        public function getElementDescription():array
        {
        }
    }
}

namespace Assay\InformationsCatalog\StructureInformation {


    use Assay;
    use Assay\Core;
    use Assay\InformationsCatalog;

    interface IStructure
    {
        const EXTERNAL_ID = 'structure_id';

        const PARENT = 'parent';

        public function addChild():int;

        public function getChildrenNames():array;

        public function getParent():int;

        public function isPartition():bool;

        public function isRubric():bool;

        public function getPath():array;

        public function getMap():array;

        public function search(string $searchString, string $structureCode):array;
    }

    interface IRubric
    {
        const EXTERNAL_ID = 'rubric_id';

        public function getMap():array;

        public function getSearchParameters():array;

        public function getProperties():array;
    }

    interface IInformationDomain
    {
        const EXTERNAL_ID = 'information_property_id';

        const TYPE_EDIT = 'type_edit';
        const SEARCH_TYPE = 'search_type';

        public function getSearchParameters():array;

    }

    class Structure extends Assay\Core\NamedEntity implements IStructure
    {
        public $parent;


        public function addEntity():int
        {

        }

        public function hideEntity():bool
        {

        }

        public function mutateEntity():bool
        {

        }

        public function getEntity(int $id):array
        {

        }

        public function getElementDescription():array
        {

        }


        public function addChild():int
        {

        }

        public function getChildrenNames():array
        {

        }

        public function getParent():int
        {

        }

        public function isPartition():bool
        {

        }

        public function isRubric():bool
        {

        }

        public function getPath():array
        {

        }

        public function getMap():array
        {

        }

        public function search(string $searchString, string $structureCode):array
        {
        }
    }

    class SearchType extends Assay\Core\NamedEntity
    {
        const EXTERNAL_ID = 'search_type_id';

        const Undefined = 0;
        const Like = 1;
        const Between = 2;
        const Enumeration = 3;

        public $value = self::Undefined;
    }

    class Rubric extends Assay\Core\NamedEntity implements IRubric
    {
        public function getMap():array
        {
        }

        public function getSearchParameters():array
        {
        }

        public function getProperties():array
        {
        }
    }

    class LinkRubricStructure extends Assay\Core\Entity
    {
        const EXTERNAL_ID = 'link_rubric_structure_id';

        const RUBRIC = Rubric::EXTERNAL_ID;
        const STRUCTURE = Structure::EXTERNAL_ID;
    }

    class TypeEdit extends Assay\Core\NamedEntity
    {
        const EXTERNAL_ID = 'type_edit_id';

        const Undefined = 0;
        const System = 1;
        const User = 2;
        const Company = 3;

        public $value = self::Undefined;
    }

    class InformationDomain extends Assay\Core\NamedEntity implements IInformationDomain
    {
        public $typeEdit;
        public $searchType = SearchType::Undefined;

        public function getSearchParameters():array
        {

        }

    }

    class RubricProperty extends Assay\Core\Entity
    {
        const EXTERNAL_ID = 'rubric_property_id';

        const PROPERTY = IInformationDomain::EXTERNAL_ID;
        const RUBRIC = Rubric::EXTERNAL_ID;
    }
}

namespace Assay\InformationsCatalog\DataInformation {

    use Assay;
    use Assay\Core;
    use Assay\InformationsCatalog;
    use Assay\InformationsCatalog\StructureInformation;

    interface IInstanceUserInformation
    {
        public function getShippingPricing():array;

        public function getGoodsPricing():array;

        public function getCompanyRubrics():array;

    }

    interface IInformationInstance
    {
        const EXTERNAL_ID = 'information_instance_id';

        const RUBRIC = 'rubric_id';

        public function getPositionByPrivileges($type = Assay\InformationsCatalog\StructureInformation\TypeEdit::Undefined):array;

        public function search(array $filterProperties, int $start, int $paging):array;
    }

    interface IDocumentForm
    {
        public function getFormPlaceholderValue():array;

        public function getDocumentForm(string $code):array;
    }

    interface ICalculateFormula
    {
        public function getFormulaArgumentValue():array;

        public function getFormulaResult(array $arguments):array;
    }

    interface ICompanySpecific
    {
        public function getMap():array;

        public function getAddress():array;
    }

    class InformationInstance extends Assay\Core\NamedEntity implements IInformationInstance, IInstanceUserInformation
    {
        public $rubricId;

        public function getShippingPricing():array
        {
        }

        public function getGoodsPricing():array
        {
        }

        public function getCompanyRubrics():array
        {
        }

        public function getPositionByPrivileges($type = Assay\InformationsCatalog\StructureInformation\TypeEdit::Undefined):array
        {
        }

        public function search(array $filterProperties, int $start, int $paging):array
        {
        }

    }

    class InformationValue extends Assay\Core\NamedEntity
    {
        const EXTERNAL_ID = 'information_value_id';

        const INSTANCE = IInformationInstance::EXTERNAL_ID;
        const PROPERTY = StructureInformation\RubricProperty::EXTERNAL_ID;
        const VALUE = 'value';

        public $instanceId;
        public $propertyId;
        public $value;

    }

    class DocumentForm implements IDocumentForm
    {

        public function getFormPlaceholderValue():array
        {
        }

        public function getDocumentForm(string $code):array
        {

        }
    }

    class CalculateFormula implements ICalculateFormula
    {

        public function getFormulaArgumentValue():array
        {
        }

        public function getFormulaResult(array $arguments):array
        {

        }
    }

    class AddressType extends Assay\Core\NamedEntity
    {
        const EXTERNAL_ID = 'address_type_id';

        const Undefined = 0;
        const Office = 1;
        const Mine = 2;
        const Construction = 3;
        const Garage = 4;

        public $value = self::Undefined;
    }

    class CompanySpecific implements ICompanySpecific
    {

        public function getMap():array
        {

        }

        public function getAddress():array
        {

        }
    }

    class CompanyAddress extends StructureInformation\Rubric
    {
        public $company;
        public $type = AddressType::Undefined;

    }
}

namespace Assay\InformationsCatalog\Permission {

    use Assay;
    use Assay\Core;
    use Assay\InformationsCatalog;
    use Assay\Permission;
    use Assay\Permission\Privilege;
    use Assay\InformationsCatalog\DataInformation;
    use Assay\InformationsCatalog\StructureInformation;

    interface IInformationRequest
    {
        public function testPrivilege():bool;
    }

    class InformationUser extends Assay\Core\Entity
    {
        const EXTERNAL_ID = 'information_user_id';

        const USER_ID = Assay\Permission\Privilege\IUser::EXTERNAL_ID;
    }

    class StructureObject extends Assay\Core\Entity
    {
        const EXTERNAL_ID = 'structure_object_id';

        const STRUCTURE = Assay\InformationsCatalog\StructureInformation\IStructure::EXTERNAL_ID;
    }

    class StructureProcess extends Assay\Core\NamedEntity
    {
        const EXTERNAL_ID = 'structure_operation_id';
    }

    class InformationPrivilege extends Assay\Core\Entity
    {
        const EXTERNAL_ID = 'Information_privilege_id';

        const ROLE_DETAIL = Privilege\RoleDetail::EXTERNAL_ID;
    }

    class StructurePrivilege extends Assay\Core\Entity
    {
        const EXTERNAL_ID = 'structure_privilege_id';

        const OPERATION = StructureProcess::EXTERNAL_ID;
        const OBJECT = StructureObject::EXTERNAL_ID;
        const INFORMATION_PRIVILEGE = InformationPrivilege::EXTERNAL_ID;
    }

    class UserValue extends Assay\InformationsCatalog\DataInformation\InformationValue
    {
        const EXTERNAL_ID = 'user_value_id';

        const USER = InformationUser::EXTERNAL_ID;
    }

    class InformationRequest implements IInformationRequest
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
        public static function calculateHash(string $password):string;

        public function authentication(string $password):bool;
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

    interface ICookies
    {
        const KEY = 'key';
        const COMPANY_FILTER = 'company_filter';
        const MODE = 'mode';
        const PAGING = 'paging';
        const USER_NAME = 'user_name';

        const EMPTY_VALUE = Assay\Core\Common::EMPTY_VALUE;

        public static function getKey():string;

        public static function getCompanyFilter():string;

        public static function getMode():string;

        public static function getPaging():string;

        public static function getUserName():string;

        public function setKey():bool;

        public function setCompanyFilter():bool;

        public function setMode():bool;

        public function setPaging():bool;

        public function setUserName():bool;

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

        public static function calculateHash(string $password, int $algorithm = self::DEFAULT_ALGORITHM):string
        {
            $result = password_hash($password,$algorithm);
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
        const EXTERNAL_ID = 'person_profile_id';

        const OBJECT = SocialObject::EXTERNAL_ID;

        public function getForGreetings():string;

        public function enableCommenting():bool;

        public function testPrivilege():bool;

        public function purgeGroup():bool;

        public function setGroup():bool;
    }

    interface ISocialGroup
    {
        const EXTERNAL_ID = 'social_group_id';

        const OBJECT = SocialObject::EXTERNAL_ID;

        public function isMember():bool;
    }

    interface ISocialElement
    {
        const SOCIAL_OBJECT = SocialObject::EXTERNAL_ID;

        public function count():int;

        public function isOwn():bool;
    }

    interface IComment
    {
        const EXTERNAL_ID = 'comment_id';

        const CONTENT = 'content';

        public function getByObject():array;
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

    interface IAd
    {

        const EXTERNAL_ID = 'ad_id';

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
        const EXTERNAL_ID = 'group_membership_id';

        const GROUP = SocialGroup::EXTERNAL_ID;
        const PROFILE = PersonProfile::EXTERNAL_ID;
    }

    class ProfileFeature extends Assay\Core\ReadableEntity
    {
        const PROFILE = Assay\Communication\Profile\IPersonProfile::EXTERNAL_ID;

        public $profile;
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
