<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 *
 * @JetDataModel:name = 'user'
 * @JetDataModel:id_class_name = 'DataModel_Id_UniqueString'
 * @JetDataModel:database_table_name = 'Jet_Auth_Users'
 */
class Auth_User extends DataModel implements Auth_User_Interface
{

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_id = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:is_key = true
	 * @JetDataModel:is_unique = true
	 * @JetDataModel:form_field_label = 'Username'
	 * @JetDataModel:form_field_error_messages = [Form_Field::ERROR_CODE_EMPTY=>'Please enter username']
	 *
	 * @var string
	 */
	protected $username = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:do_not_export = true
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:is_key = true
	 * @JetDataModel:form_field_type = Form::TYPE_REGISTRATION_PASSWORD
	 * @JetDataModel:form_field_label = 'Password'
	 * @JetDataModel:form_field_options = ['password_confirmation_label'=>'Confirm password']
	 * @JetDataModel:form_field_error_messages = [Form_Field_RegistrationPassword::ERROR_CODE_EMPTY=>'Please enter password', Form_Field_RegistrationPassword::ERROR_CODE_CHECK_EMPTY=>'Please enter confirm password', Form_Field_RegistrationPassword::ERROR_CODE_CHECK_NOT_MATCH=>'Passwords do not match']
	 *
	 * @var string
	 */
	protected $password = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 * @JetDataModel:default_value = false
	 * @JetDataModel:form_field_type = false
	 *
	 * @var bool
	 */
	protected $is_superuser = false;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'E-mail'
	 * @JetDataModel:form_field_is_required = true
	 *
	 * @var string
	 */
	protected $email = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_LOCALE
	 * @JetDataModel:form_field_label = 'Locale'
	 * @JetDataModel:form_field_get_select_options_callback = [ 'Mvc_Site','getAllLocalesList']
	 * @JetDataModel:form_field_error_messages = [Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select locale']
	 *
	 * @var Locale
	 */
	protected $locale;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:form_field_label = 'First name'
	 *
	 * @var string
	 */
	protected $first_name = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:form_field_label = 'Surname'
	 *
	 * @var string
	 */
	protected $surname = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 65536
	 * @JetDataModel:form_field_label = 'Description'
	 *
	 * @var string
	 */
	protected $description = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 * @JetDataModel:default_value = true
	 * @JetDataModel:form_field_label = 'Password is valid'
	 *
	 * @var bool
	 */
	protected $password_is_valid = true;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATE_TIME
	 * @JetDataModel:default_value = null
	 * @JetDataModel:form_field_label = 'Password is valid till'
	 *
	 * @var Data_DateTime
	 */
	protected $password_is_valid_till;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 * @JetDataModel:default_value = false
	 * @JetDataModel:form_field_label = 'User is blocked'
	 *
	 * @var bool
	 */
	protected $user_is_blocked = false;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATE_TIME
	 * @JetDataModel:default_value = null
	 * @JetDataModel:form_field_label = 'User is blocked till'
	 *
	 * @var Data_DateTime
	 */
	protected $user_is_blocked_till;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 * @JetDataModel:default_value = true
	 * @JetDataModel:form_field_label = 'User is activated'
	 *
	 * @var bool
	 */
	protected $user_is_activated = true;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:default_value = ''
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $user_activation_hash = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Auth_User_Roles'
	 * @JetDataModel:form_field_creator_method_name = 'createRolesFormField'
	 * @JetDataModel:form_field_type = Form::TYPE_MULTI_SELECT
	 * @JetDataModel:form_field_label = 'Roles'
	 * @JetDataModel:form_field_get_select_options_callback = ['Auth_Role', 'getList']
	 * @JetDataModel:form_setter_name = 'setRoles'
	 * @JetDataModel:form_field_error_messages = [Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select role']
	 *
	 * @var Auth_User_Roles|DataModel_Related_MtoN_Iterator|Auth_Role[]
	 */
	protected $roles;

	/**
	 * @param string|null $username
	 * @param string|null $password
	 */
	public function __construct( $username = null, $password = null )
	{

		if( $username!==null ) {
			$this->setUsername( $username );
		}
		if( $password!==null ) {
			$this->setPassword( $password );
		}

		parent::__construct();
	}

	/**
	 * @param string $password
	 */
	public function setPassword( $password )
	{
		if( $password ) {
			$this->password = $this->encryptPassword( $password );
		}
	}

	/**
	 * @param string $password
	 *
	 * @return string
	 */
	public function encryptPassword( $password )
	{
		return password_hash( $password, PASSWORD_DEFAULT );
	}

	/**
	 * @param string $id
	 *
	 * @return Auth_User
	 */
	public static function get( $id )
	{
		/**
		 * @var Auth_User $user
		 */
		$user = static::load( $id );

		return $user;
	}

	/**
	 * @param string|null $role_id (optional)
	 * @param string      $search
	 *
	 * @return Auth_User[]|DataModel_Fetch_Object_Assoc
	 */
	public static function getList( $role_id = null, $search = '' )
	{
		$query = [];

		if( $role_id ) {
			$query = [
				'Auth_Role.id' => $role_id,
			];
		}

		if( $search ) {
			if( $query ) {
				$query [] = 'AND';
			}

			$search = '%'.$search.'%';
			$query[] = [
				'this.username *'    => $search, 'OR', 'this.first_name *' => $search, 'OR',
				'this.surname *'     => $search, 'OR', 'this.email *' => $search,
			];
		}

		/**
		 * @var Auth_User $_this
		 */
		$_this = new static();
		$list = $_this->fetchObjects( $query );
		$list->setLoadFilter(
			[
				'this.id', 'this.username', 'this.first_name', 'this.surname', 'this.locale',
			]
		);
		$list->getQuery()->setOrderBy( 'username' );

		return $list;

	}

	/**
	 * @param string $username
	 * @param string $password
	 *
	 * @return Auth_User|bool
	 */
	public static function getByIdentity( $username, $password )
	{

		/**
		 * @var Auth_User $user
		 */
		$user = ( new static() )->fetchOneObject(
			[
				'this.username' => $username,
			]
		);

		if( !$user ) {
			return false;
		}

		if( !$user->verifyPassword( $password ) ) {
			return false;
		}

		return $user;
	}

	/**
	 * @param string $plain_password
	 *
	 * @return bool
	 */
	public function verifyPassword( $plain_password )
	{
		return password_verify( $plain_password, $this->password );
	}

	/**
	 * @param string $username
	 *
	 * @return Auth_User|bool
	 */
	public static function getGetByUsername( $username )
	{
		/**
		 * @var Auth_User $user
		 */
		$user = ( new static() )->fetchOneObject(
			[
				'this.username' => $username,
			]
		);

		return $user;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->getIdObject()->toString();
	}

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername( $username )
	{
		$this->username = $username;
	}

	/**
	 * @param string $password
	 *
	 * @return bool
	 */
	public function verifyPasswordStrength( $password )
	{
		return true;
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail( $email )
	{
		$this->email = $email;
	}

	/**
	 * @return Locale
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 * @param string|Locale $locale
	 */
	public function setLocale( $locale )
	{
		if( !( $locale instanceof Locale ) ) {
			$locale = new Locale( $locale );
		}
		$this->locale = $locale;
	}

	/**
	 * @return string
	 */
	public function getFirstName()
	{
		return $this->first_name;
	}

	/**
	 * @param string $first_name
	 */
	public function setFirstName( $first_name )
	{
		$this->first_name = $first_name;
	}

	/**
	 * @return string
	 */
	public function getSurname()
	{
		return $this->surname;
	}

	/**
	 * @param string $surname
	 */
	public function setSurname( $surname )
	{
		$this->surname = $surname;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->first_name.' '.$this->surname;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription( $description )
	{
		$this->description = $description;
	}

	/**
	 * @return bool
	 */
	public function getPasswordIsValid()
	{
		return $this->password_is_valid;
	}

	/**
	 * @param bool $password_is_valid
	 */
	public function setPasswordIsValid( $password_is_valid )
	{
		$this->password_is_valid = (bool)$password_is_valid;
	}

	/**
	 *
	 * @return Data_DateTime
	 */
	public function getPasswordIsValidTill()
	{
		return $this->password_is_valid_till;
	}

	/**
	 * @param Data_DateTime|string $password_is_valid_till
	 *
	 * @return void
	 */
	public function setPasswordIsValidTill( $password_is_valid_till )
	{
		if( !$password_is_valid_till ) {
			$this->password_is_valid_till = null;
		} else {
			$this->password_is_valid_till = new Data_DateTime( $password_is_valid_till );
		}
	}

	/**
	 * @return bool
	 */
	public function isBlocked()
	{
		return $this->user_is_blocked;
	}

	/**
	 * @return null|Data_DateTime
	 */
	public function isBlockedTill()
	{
		return $this->user_is_blocked_till;
	}

	/**
	 * @param string|Data_DateTime|null $till
	 */
	public function block( $till = null )
	{
		$this->user_is_blocked = true;
		if( !$till ) {
			$this->user_is_blocked_till = null;
		} else {
			$this->user_is_blocked_till = new Data_DateTime( $till );
		}
	}

	/**
	 *
	 */
	public function unBlock()
	{
		$this->user_is_blocked = false;
		$this->user_is_blocked_till = null;
	}

	/**
	 * @return bool
	 */
	public function isActivated()
	{
		return $this->user_is_activated;
	}

	/**
	 * @param string|null $user_activation_hash (optional)
	 *
	 * @return bool
	 */
	public function activate( $user_activation_hash = null )
	{
		if(
			$user_activation_hash &&
			$this->user_activation_hash!=$user_activation_hash
		) {
			return false;
		}
		$this->user_is_activated = true;

		return true;
	}

	/**
	 * @return string
	 */
	public function getActivationHash()
	{
		return $this->user_activation_hash;
	}

	/**
	 * @param string $user_activation_hash
	 */
	public function setActivationHash( $user_activation_hash )
	{
		$this->user_activation_hash = $user_activation_hash;
	}

	/**
	 * @param DataModel_Definition_Property $property_definition
	 *
	 * @return Form_Field
	 */
	public function createRolesFormField( DataModel_Definition_Property $property_definition )
	{

		return $property_definition->createFormField( $this->roles );
	}

	/**
	 * @return Auth_Role[]
	 */
	public function getRoles()
	{
		return $this->roles;
	}

	/**
	 * @param array $roles_ids
	 */
	public function setRoles( array $roles_ids )
	{
		$roles = [];

		foreach( $roles_ids as $role_id ) {
			/**
			 * @var DataModel_Definition_Property_DataModel $roles_property_def
			 */
			$roles_property_def = static::getDataModelDefinition()->getProperty( 'roles' );
			/**
			 * @var DataModel_Definition_Model_Related_MtoN $role_definition
			 */
			$role_definition = $roles_property_def->getValueDataModelDefinition();

			$role_class_name = $role_definition->getNModelClassName();

			/**
			 * @var callable $call_back
			 */
			$call_back = [ $role_class_name, 'get' ];

			$role = $call_back( $role_id );
			if( !$role ) {
				continue;
			}
			$roles[] = $role;
		}
		$this->roles->setItems( $roles );

	}

	/**
	 * @param string $role_id
	 *
	 * @return bool
	 */
	public function hasRole( $role_id )
	{
		foreach( $this->roles as $role ) {
			/**
			 * @var Auth_Role_Interface $role
			 */
			if( $role->getId()==$role_id ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $privilege
	 * @param mixed  $value
	 *
	 * @return bool
	 */
	public function hasPrivilege( $privilege, $value )
	{

		if( $this->getIsSuperuser() ) {
			return true;
		}

		foreach( $this->roles as $role ) {
			/**
			 * @var Auth_Role_Interface $role
			 */
			if( $role->hasPrivilege( $privilege, $value ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function getIsSuperuser()
	{
		return $this->is_superuser;
	}

	/**
	 * @param bool $is_superuser
	 */
	public function setIsSuperuser( $is_superuser )
	{
		$this->is_superuser = (bool)$is_superuser;
	}

	/**
	 * @param string $privilege
	 *
	 * @return array
	 */
	public function getPrivilegeValues( $privilege )
	{
		$result = [];
		foreach( $this->roles as $role ) {
			/**
			 * @var Auth_Role_Interface $role
			 */

			$result = array_merge(
				$role->getPrivilegeValues( $privilege ), $result
			);
		}

		$result = array_unique( $result );

		return $result;
	}

	/**
	 * @param string $form_name
	 *
	 * @return Form
	 */
	public function getEditForm( $form_name = '' )
	{
		$form = $this->getCommonForm( $form_name );

		if( $this->getIsNew() ) {
			/**
			 * @var Form_Field_RegistrationPassword $password_field
			 */
			$password_field = $form->getField( 'password' );
			$password_field->setIsRequired( true );
			$password_field->setPasswordStrengthCheckCallback( [ $this, 'verifyPasswordStrength' ] );
			$password_field->setErrorMessages(
				[
					Form_Field_RegistrationPassword::ERROR_CODE_WEAK_PASSWORD => 'Password is not strong enough',
				]
			);

			$form->getField( 'password_is_valid' )->setDefaultValue( false );
		} else {
			$form->removeField( 'password' );
		}

		$user = $this;

		$form->getField( 'password_is_valid_till' )->setErrorMessages(
			[
				Form_Field_DateTime::ERROR_CODE_INVALID_FORMAT => 'Invalid format',
			]
		);
		$form->getField( 'user_is_blocked_till' )->setErrorMessages(
			[
				Form_Field_DateTime::ERROR_CODE_INVALID_FORMAT => 'Invalid format',
			]
		);

		$form->getField( 'locale' )->setIsRequired( true );
		$form->getField( 'locale' )->setErrorMessages(
			[
				Form_Field_Select::ERROR_CODE_EMPTY => 'Please select locale',
			]
		);

		$form->getField( 'username' )->setValidator(
			function( Form_Field $field ) use ( $user ) {
				$username = $field->getValue();

				/** @var $user Auth_User */
				if( $user->usernameExists( $username ) ) {
					$field->setCustomError(
						Tr::_(
							'Sorry, but username %USERNAME% is registered.', [ 'USERNAME' => $username ]
						)
					);

					return false;
				}

				return true;
			}
		);

		$form->getField( 'email' )->setErrorMessages(
			[
				Form_Field_Email::ERROR_CODE_EMPTY          => 'Please enter valid e-mail address',
				Form_Field_Email::ERROR_CODE_INVALID_FORMAT => 'Please enter valid e-mail address',
			]
		);

		return $form;
	}

	/**
	 *
	 * @param string $username
	 *
	 * @return bool
	 */
	public function usernameExists( $username )
	{
		if( $this->getIsNew() ) {
			$q = [
				'this.username' => $username,
			];
		} else {
			$q = [
				'this.username' => $username, 'AND', 'this.id!=' => $this->id,
			];
		}

		return (bool)static::getBackendInstance()->getCount( $this->createQuery( $q ) );
	}

	/**
	 *
	 */
	public function afterAdd()
	{
	}

	/**
	 *
	 */
	public function afterUpdate()
	{
	}

	/**
	 *
	 */
	public function afterDelete()
	{
	}

}