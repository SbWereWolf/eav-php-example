<?php
namespace Assay\Core {

    interface ICommon
    {
        public static function IsSetEx($valueIfIsset, $valueIfNotIsset);
        public static function SetIfExists($key, &$array, $valueIfNotIsset);
    }

    class Common implements ICommon
    {
        const EMPTY_VALUE = '';
        const EMPTY_OBJECT = null;

        public static function IsSetEx($valueIfIsset, $valueIfNotIsset)
        {
            $value = isset($valueIfIsset) ? $valueIfIsset : $valueIfNotIsset;
            return $value;
        }

        public static function SetIfExists($key, &$array, $valueIfNotIsset)
        {
            $value = $valueIfNotIsset;
            $maySet = array_key_exists($key, $array);
            if ($maySet) {
                $value = self::IsSetEx($array[$key], $valueIfNotIsset);
            }
            return $value;
        }
    }

    interface IEntity
    {

        const ID = 'id';
        const IS_HIDDEN = 'is_hidden';
        const INSERT_DATE = 'insert_date';

        const DEFAULT_IS_HIDDEN = false;

        public function AddEntity():int;

        public function HideEntity():bool;
    }

    class Entity implements IEntity
    {

        const TABLE_NAME = 'entity_table';

        public $id;
        public $isHidden;
        public $insertDate;

        public function AddEntity():int
        {
            $result = 0;
            return $result;
        }

        public function HideEntity():bool
        {
        }
    }

    interface IReadableEntity
    {

        public function ReadEntity(string $id):array;

        public function GetStored():array;

        public function SetByNamedValue(array $namedValue);
    }

    class ReadableEntity extends Entity implements IReadableEntity
    {

        public function ReadEntity(string $id):array
        {
        }

        public function GetStored():array
        {
            $result = array();
            return $result;
        }

        public function SetByNamedValue(array $namedValue)
        {
        }
    }

    interface IMutableEntity
    {
        public function MutateEntity():bool;

        public function ToEntity():array;

    }

    class MutableEntity extends Entity implements IMutableEntity, IReadableEntity
    {
        public function MutateEntity():bool
        {

        }

        public function ReadEntity(string $id):array
        {
            $result = array();
            return $result;
        }

        public function GetStored():array
        {
            $result = array();
            return $result;
        }

        public function SetByNamedValue(array $namedValue)
        {
        }

        public function ToEntity():array
        {
            $result = array();
            return $result;
        }

    }

    interface INamedEntity
    {
        const CODE = 'code';
        const NAME = 'name';
        const DESCRIPTION = 'description';

        public function LoadByCode(string $code):array;

        public function GetElementDescription():array;

    }

    class NamedEntity extends MutableEntity implements INamedEntity
    {
        public $code;
        public $name;
        public $description;

        public function LoadByCode(string $code):array
        {
        }

        public function GetElementDescription():array
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

        public function AddChild():int;

        public function GetChildrenNames():array;

        public function GetParent():int;

        public function IsPartition():bool;

        public function IsRubric():bool;

        public function GetPath():array;

        public function GetMap():array;

        public function Search(string $searchString, string $structureCode):array;
    }

    class Structure extends Assay\Core\NamedEntity implements IStructure
    {
        public $parent;


        public function AddEntity():int
        {

        }

        public function HideEntity():bool
        {

        }

        public function MutateEntity():bool
        {

        }

        public function GetEntity(int $id):array
        {

        }

        public function GetElementDescription():array
        {

        }


        public function AddChild():int
        {

        }

        public function GetChildrenNames():array
        {

        }

        public function GetParent():int
        {

        }

        public function IsPartition():bool
        {

        }

        public function IsRubric():bool
        {

        }

        public function GetPath():array
        {

        }

        public function GetMap():array
        {

        }

        public function Search(string $searchString, string $structureCode):array
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

    interface IRubric
    {
        const EXTERNAL_ID = 'rubric_id';

        public function GetMap():array;

        public function GetSearchParameters():array;

        public function GetProperties():array;
    }

    class Rubric extends Assay\Core\NamedEntity implements IRubric
    {
        public function GetMap():array
        {
        }

        public function GetSearchParameters():array
        {
        }

        public function GetProperties():array
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

    interface IInformationDomain
    {
        const EXTERNAL_ID = 'information_property_id';

        const TYPE_EDIT = 'type_edit';
        const SEARCH_TYPE = 'search_type';

        public function GetSearchParameters():array;

    }

    class InformationDomain extends Assay\Core\NamedEntity implements IInformationDomain
    {
        public $typeEdit;
        public $searchType = SearchType::Undefined;

        public function GetSearchParameters():array
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
        public function GetShippingPricing():array;

        public function GetGoodsPricing():array;

        public function GetCompanyRubrics():array;

    }

    interface IInformationInstance
    {
        const EXTERNAL_ID = 'information_instance_id';

        const RUBRIC = 'rubric_id';

        public function GetPositionByPrivileges($type = Assay\InformationsCatalog\StructureInformation\TypeEdit::Undefined):array;

        public function Search(array $filterProperties, int $start, int $paging):array;
    }

    class InformationInstance extends Assay\Core\NamedEntity implements IInformationInstance, IInstanceUserInformation
    {
        public $rubricId;

        public function GetShippingPricing():array
        {
        }

        public function GetGoodsPricing():array
        {
        }

        public function GetCompanyRubrics():array
        {
        }

        public function GetPositionByPrivileges($type = Assay\InformationsCatalog\StructureInformation\TypeEdit::Undefined):array
        {
        }

        public function Search(array $filterProperties, int $start, int $paging):array
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

    interface IDocumentForm
    {
        public function GetFormPlaceholderValue():array;

        public function GetDocumentForm(string $code):array;
    }

    class DocumentForm implements IDocumentForm
    {

        public function GetFormPlaceholderValue():array
        {
        }

        public function GetDocumentForm(string $code):array
        {

        }
    }

    interface ICalculateFormula
    {
        public function GetFormulaArgumentValue():array;

        public function GetFormulaResult(array $arguments):array;
    }

    class CalculateFormula implements ICalculateFormula
    {

        public function GetFormulaArgumentValue():array
        {
        }

        public function GetFormulaResult(array $arguments):array
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

    interface ICompanySpecific
    {
        public function GetMap():array;

        public function GetAddress():array;
    }

    class CompanySpecific implements ICompanySpecific
    {

        public function GetMap():array
        {

        }

        public function GetAddress():array
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

    interface IInformationRequest
    {
        public function TestPrivilege():bool;
    }

    class InformationRequest implements IInformationRequest
    {
        public $session;
        public $process;
        public $object;
        public $content;

        public function TestPrivilege():bool
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
        
        public function Registration(string $login, string $password, string $passwordConfirmation, string $email):bool;

        public function ChangePassword(string $newPassword):bool;

        public function RecoveryPassword():bool;

        public function UpdateActivityDate():bool;

        public function GetStored():array;

        public function SetByDefault():bool;

        public function LoadByEmail(string $email):bool;

        public function SendRecovery():bool;
    }

    interface IAuthenticateUser
    {
        public function Authentication(string $password):bool;

        public static function CalculateHash(string $password):string;
    }

    class User extends Assay\Core\MutableEntity implements IUser, IAuthenticateUser
    {
        const TABLE_NAME = 'authentic_user';

        public $login;
        public $passwordHash;
        public $activityDate;
        public $email;

        public function Registration(string $login, string $password, string $passwordConfirmation, string $email):bool
        {
            $result = false;
            
            $isCorrectPassword = $password == $passwordConfirmation;
            if ($isCorrectPassword) {
                $this->activityDate = time();
                $this->email = $email;
                $this->isHidden = Core\IEntity::DEFAULT_IS_HIDDEN;
                $this->login = $login;
                $this->passwordHash = self::CalculateHash($password);

                $this->id = $this->AddEntity();
                $result = $this->MutateEntity();
            }

            return $result;
        }

        public function ChangePassword(string $newPassword):bool
        {
            $this->passwordHash=self::CalculateHash($newPassword);
            $result = $this->MutateEntity();
            return $result;
        }

        public function RecoveryPassword():bool
        {
            $result = true;
            // SEND EMAIL WITH RECOVERY LINK;
            return $result;
        }

        public function UpdateActivityDate():bool
        {
            $this->activityDate = time();
            $result = $this->MutateEntity();

            return $result;
        }

        public function Authentication(string $password):bool
        {
            $result =  password_verify($password,$this->passwordHash);

            return $result;
        }

        public static function CalculateHash(string $password, int $algorithm=self::DEFAULT_ALGORITHM):string
        {
            $result = password_hash($password,$algorithm);
            return $result;
        }

        public function SetByDefault():bool
        {
            // SET TO GUEST USER ;
        }

        public function SetByNamedValue(array $namedValue)
        {
            $this->id = Core\Common::SetIfExists(self::ID, $namedValue, self::EMPTY_VALUE);
            $this->insertDate = Core\Common::SetIfExists(self::INSERT_DATE, $namedValue, self::EMPTY_VALUE);
            $this->isHidden = Core\Common::SetIfExists(self::IS_HIDDEN, $namedValue, self::EMPTY_VALUE);
            $this->login = Core\Common::SetIfExists(self::LOGIN, $namedValue, self::EMPTY_VALUE);
            $this->passwordHash = Core\Common::SetIfExists(self::PASSWORD_HASH, $namedValue, self::EMPTY_VALUE);
            $this->activityDate = Core\Common::SetIfExists(self::ACTIVITY_DATE, $namedValue, self::EMPTY_VALUE);
            $this->email = Core\Common::SetIfExists(self::EMAIL, $namedValue, self::EMPTY_VALUE);
        }

        public function MutateEntity():bool
        {
            $storedData = $this->GetStored();
            $entity = $this->ToEntity();

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

        public function ToEntity():array
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

        public function LoadByEmail(string $email):bool
        {
            $result = true;
            return $result;
        }

        public function SendRecovery():bool
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

        public function GrantRole(string $role):bool;

        public function RevokeRole(string $role):bool;

    }

    interface IAuthorizeProcess
    {
        public function UserAuthorization(string $process, string $object):bool;
    }

    class UserRole extends Assay\Core\Entity implements IUserRole, IAuthorizeProcess
    {
        public $userId;

        public function __construct(string $userId)
        {
            $this->userId = $userId;
        }

        public function GrantRole(string $role):bool
        {
        }

        public function RevokeRole(string $role):bool
        {
        }

        public function UserAuthorization(string $process, string $object):bool
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

        public function TestPrivilege():bool;
    }

    class ProcessRequest implements IProcessRequest
    {
        public $session;
        public $process;
        public $object;
        public $content;

        public function TestPrivilege():bool
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

        public function SetKey():bool;

        public static function GetKey():string;

        public function SetCompanyFilter():bool;

        public static function GetCompanyFilter():string;

        public function SetMode():bool;

        public static function GetMode():string;

        public function SetPaging():bool;

        public static function GetPaging():string;

        public function SetUserName():bool;

        public static function GetUserName():string;

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
            $this->key = self::GetKey();
            $this->companyFilter = self::GetCompanyFilter();
            $this->mode = self::GetMode();
            $this->paging = self::GetPaging();
            $this->userName = self::GetUserName();
        }

        public function SetKey():bool
        {
        }

        public static function GetKey():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public function SetCompanyFilter():bool
        {
        }

        public static function GetCompanyFilter():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public function SetMode():bool
        {
        }

        public static function GetMode():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public function SetPaging():bool
        {
        }

        public static function GetPaging():string
        {
            $result = ICookies::EMPTY_VALUE;
            return $result;
        }

        public function SetUserName():bool
        {
        }

        public static function GetUserName():string
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

        public static function Open(string $userId):array;

        public function Close():bool;
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

        public function SetByCookie(Cookie $cookies)
        {
            $this->cookies = $cookies;

            $this->key = $this->cookies->key;
            $this->companyFilter = $this->cookies->companyFilter;
            $this->mode = $this->cookies->mode;
            $this->paging = $this->cookies->paging;
            $this->userName = $this->cookies->userName;
        }

        public function SetByNamedValue(array $namedValues)
        {
            $this->key = Assay\Core\Common::SetIfExists(
                self::KEY, $namedValues, ISession::EMPTY_VALUE
            );
            $this->companyFilter = Assay\Core\Common::SetIfExists(
                self::COMPANY_FILTER, $namedValues, ISession::EMPTY_VALUE
            );
            $this->mode = Assay\Core\Common::SetIfExists(
                self::MODE, $namedValues, ISession::EMPTY_VALUE
            );
            $this->paging = Assay\Core\Common::SetIfExists(
                self::PAGING, $namedValues, ISession::EMPTY_VALUE
            );
            $this->userName = Assay\Core\Common::SetIfExists(
                self::USER_NAME, $namedValues, ISession::EMPTY_VALUE
            );
            $this->userId = Assay\Core\Common::SetIfExists(
                self::USER_ID, $namedValues, ISession::EMPTY_VALUE
            );
        }

        public function GetStored():array
        {
            $result = array();
            return $result;
        }

        public function MutateEntity():bool
        {
            $storedData = $this->GetStored();
            $entity = $this->ToEntity();

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

        public function ToEntity():array
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

        public static function Open(string $userId):array
        {
            $process = self::OPEN_PROCESS;
            $object = self::SESSION_OBJECT;
            $result = array();

            $userRole = new UserRole($userId);
            $isAllow = $userRole->UserAuthorization($process, $object);
            if($isAllow){
                $result[self::USER_ID]=$userId;
                $key = uniqid('',true);
                $result[self::KEY]=$key;

                $session = new Session();
                $id = $session->AddEntity();
                $result[self::ID] = $id;

                $session->SetByNamedValue($result);
                $session->MutateEntity();
            }
            return $result;
        }

        public function Close():bool
        {
            $result = $this->HideEntity();
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

        public function GetForGreetings():string;

        public function EnableCommenting():bool;

        public function TestPrivilege():bool;

        public function PurgeGroup():bool;

        public function SetGroup():bool;
    }

    class PersonProfile extends Assay\Core\NamedEntity implements IPersonProfile
    {
        public function GetForGreetings():string
        {
        }

        public function EnableCommenting():bool
        {
        }

        public function TestPrivilege():bool
        {
        }

        public function PurgeGroup():bool
        {
        }

        public function SetGroup():bool
        {
        }
    }

    interface ISocialGroup
    {
        const EXTERNAL_ID = 'social_group_id';

        const OBJECT = SocialObject::EXTERNAL_ID;

        public function IsMember():bool;
    }

    class SocialGroup extends Assay\Core\NamedEntity implements ISocialGroup
    {
        public function IsMember():bool
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

        public function Count():int;

        public function IsOwn():bool;
    }

    class SocialElement extends ProfileFeature implements ISocialElement
    {
        public $object;

        public function Count():int
        {
        }

        public function IsOwn():bool
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

        public function GetByObject():array;
    }

    class Comment extends SocialElement implements IComment
    {
        public function GetByObject():array
        {
        }
    }

    interface IMessage
    {
        const EXTERNAL_ID = 'message_id';

        const CONTENT = 'content';

        public function GetCorrespondent():array;

        public function GetByCorrespondent():array;

        public function SaveGoodsOrder():bool;

        public function SaveShippingOrder():bool;
    }

    class Message extends SocialElement implements IMessage
    {
        const GOODS_ORDER_PATTERN = 'GOODS_ORDER_PATTERN';
        const SHIPPING_ORDER_PATTERN = 'SHIPPING_ORDER_PATTERN';

        public $correspondent;

        public function GetCorrespondent():array
        {
        }

        public function GetByCorrespondent():array
        {
        }

        public function SaveGoodsOrder():bool
        {
        }

        public function SaveShippingOrder():bool
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

        public function Purge():bool;
    }

    class Ad extends ProfileFeature implements IAd
    {

        public $content;
        public $social_object;

        public function Purge():bool
        {
        }
    }

    interface ISocialBlock
    {
        public function GetCounter():array;

        public function GetComment():array;
    }

    class SocialBlock implements ISocialBlock
    {

        public function GetCounter():array
        {
        }

        public function GetComment():array
        {
        }
    }

    interface ISocialAction
    {
        public function SocialAction():bool;
    }

    class SocialAction implements ISocialAction
    {
        public function SocialAction():bool
        {
        }
    }

    interface IProfile
    {

        public function GetCommentEnableArea():bool;
    }

    class Profile implements IProfile
    {

        public $profile;

        public function GetCommentEnableArea():bool
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
        public function TestPrivilege():bool;
    }

    class CommunicationRequest implements ICommunicationRequest
    {
        public $session;
        public $process;
        public $object;
        public $content;

        public function TestPrivilege():bool
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
