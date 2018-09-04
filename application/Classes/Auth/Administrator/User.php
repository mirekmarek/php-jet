<?php
namespace JetApplication;

use Jet\Auth_User_Interface;
use Jet\DataModel;
use Jet\DataModel_Id_AutoIncrement;
use Jet\DataModel_Related_MtoN_Iterator;
use Jet\DataModel_Fetch_Instances;
use Jet\Form;
use Jet\Form_Field_Email;
use Jet\Form_Field_Input;
use Jet\Form_Field_MultiSelect;
use Jet\Form_Field_RegistrationPassword;
use Jet\Form_Field_DateTime;
use Jet\Form_Field_Select;
use Jet\Data_DateTime;
use Jet\Locale;
use Jet\Mailing_Email;
use Jet\Tr;

/**
 *
 * @JetDataModel:name = 'user'
 * @JetDataModel:database_table_name = 'users_administrators'
 * @JetDataModel:id_class_name = 'DataModel_Id_AutoIncrement'
 * @JetDataModel:id_options = ['id_property_name'=>'id']
 */
class Auth_Administrator_User extends DataModel implements Auth_User_Interface
{

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID_AUTOINCREMENT
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
	 * @JetDataModel:form_field_error_messages = [Form_Field_Input::ERROR_CODE_EMPTY=>'Please enter username']
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
	 * @JetDataModel:form_field_error_messages = [Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter e-mail address',Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Please enter e-mail address']
	 *
	 * @var string
	 */
	protected $email = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_LOCALE
	 * @JetDataModel:form_field_label = 'Locale'
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:form_field_error_messages = [Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select locale',Form_Field_Select::ERROR_CODE_EMPTY => 'Please select locale']
	 * @JetDataModel:form_field_get_select_options_callback = ['this', 'getLocales']
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
	 * @JetDataModel:form_field_error_messages = [Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid date format']
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
	 * @JetDataModel:form_field_error_messages = [Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid date format']
	 *
	 * @var Data_DateTime
	 */
	protected $user_is_blocked_till;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Auth_Administrator_User_Roles'
	 *
	 * @var Auth_Administrator_User_Roles|DataModel_Related_MtoN_Iterator|Auth_Administrator_Role[]
	 *
	 */
	protected $roles;



	/**
	 * @var Form
	 */
	protected $_form_add;
	/**
	 * @var Form
	 */
	protected $_form_edit;


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
	 * @return Auth_Administrator_User
	 */
	public static function get( $id )
	{
		/**
		 * @var Auth_Administrator_User $user
		 */
		$user = static::load( $id );

		return $user;
	}

	/**
	 * @param string|null $role_id (optional)
	 * @param string      $search
	 *
	 * @return Auth_Administrator_User[]|DataModel_Fetch_Instances
	 */
	public static function getList( $role_id = null, $search = '' )
	{
		$where = [];

		if( $role_id ) {
			$where = [
				'Auth_Role.id' => $role_id,
			];
		}

		if( $search ) {
			if( $where ) {
				$where [] = 'AND';
			}

			$search = '%'.$search.'%';
			$where[] = [
				'username *'   => $search,
				'OR',
				'first_name *' => $search,
				'OR',
				'surname *'    => $search,
				'OR',
				'email *'      => $search,
			];
		}


		$list = static::fetchInstances( $where );
		$list->setLoadFilter(
			[
				'id',
				'username',
				'first_name',
				'surname',
				'locale',
			]
		);
		$list->getQuery()->setOrderBy( 'username' );

		return $list;

	}

	/**
	 * @param string $username
	 * @param string $password
	 *
	 * @return Auth_Administrator_User|bool
	 */
	public static function getByIdentity( $username, $password )
	{

		/**
		 * @var Auth_Administrator_User $user
		 */
		$user = static::load(
			[
				'username' => $username,
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
	 * @return Auth_Administrator_User|bool
	 */
	public static function getGetByUsername( $username )
	{
		/**
		 * @var Auth_Administrator_User $user
		 */
		$user = static::load(
			[
				'username' => $username,
			]
		);

		return $user;
	}

	/**
	 * @return int
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
	 * @return Auth_Administrator_Role[]
	 */
	public function getRoles()
	{
		return $this->roles;
	}

	/**
	 * @param array $role_ids
	 */
	public function setRoles( array $role_ids )
	{
		$roles = [];

		foreach( $role_ids as $role_id ) {

			$role = Auth_Administrator_Role::get( $role_id );

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
			$result = array_merge(
				$role->getPrivilegeValues( $privilege ), $result
			);
		}

		$result = array_unique( $result );

		return $result;
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
				'username' => $username,
			];
		} else {
			$q = [
				'username' => $username,
				'AND',
				'id!='     => $this->id,
			];
		}

		return (bool)static::getBackendInstance()->getCount( static::createQuery( $q ) );
	}



	/**
	 *
	 */
	public function resetPassword()
	{

		$password = static::generatePassword();

		$this->setPassword( $password );
		$this->setPasswordIsValid( false );
		$this->save();


		$email = new Mailing_Email(
			'user_password_reset',
			$this->getLocale(),
			Application_Admin::getSiteId()
		);

		$email->setVar('user', $this);
		$email->setVar('password', $password);

		$email->send( $this->getEmail() );
	}

	/**
	 * @return string
	 */
	public static function generatePassword()
	{
		srand();
		$password = '';
		$length = rand( 8, 12 );

		$chars = [
			0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o',
			'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
			'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '@', '#', '$', '%', '*',
		];

		for( $l = 0; $l<$length; $l++ ) {
			$password .= $chars[rand( 1, count( $chars ) )-1];
		}

		return $password;
	}

	/**
	 * @param string $password
	 *
	 * @return bool
	 */
	public function verifyPasswordStrength( $password )
	{
		if( strlen( $password )<5 ) {
			return false;
		}

		return true;
	}


	/**
	 *
	 *
	 * @return Form
	 */
	public function _getForm()
	{

		$form = $this->getCommonForm();

		$roles = new Form_Field_MultiSelect('roles', 'Roles', $this->roles);
		$roles->setSelectOptions( Auth_Administrator_Role::getList() );
		$roles->setCatcher( function($value) {
			$this->setRoles( $value );
		} );
		$roles->setErrorMessages([
			Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE => "Please select role",
		]);
		$form->addField( $roles );


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



		$form->getField( 'username' )->setValidator(
			function( Form_Field_Input $field ) {
				$username = $field->getValue();

				if( $this->usernameExists( $username ) ) {
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


		return $form;
	}

	/**
	 *
	 * @return Form
	 */
	public function getRegistrationForm()
	{
		$form = $this->_getForm();
		$form->setName('register_user');

		foreach( $form->getFields() as $field ) {
			if( !in_array( $field->getName(), [ 'username', 'locale', 'password', 'email' ] ) ) {
				$form->removeField( $field->getName() );
			}
		}

		$form->getField( 'locale' )->setDefaultValue( Locale::getCurrentLocale() );

		/**
		 * @var Form_Field_RegistrationPassword $pwd
		 */
		$pwd = $form->getField( 'password' );
		$pwd->setPasswordConfirmationLabel('Confirm password');

		return $form;
	}


	/**
	 *
	 * @return Form
	 */
	public function getEditForm()
	{
		if(!$this->_form_edit) {
			$form = $this->_getForm();
			$form->setName('_user');

			if( $form->fieldExists( 'password' ) ) {
				$form->removeField( 'password' );
			}



			$this->_form_edit = $form;
		}

		return $this->_form_edit;
	}

	/**
	 * @return bool
	 */
	public function catchEditForm()
	{
		return $this->catchForm( $this->getEditForm() );
	}


	/**
	 * @return Form
	 */
	public function getAddForm()
	{
		if(!$this->_form_add) {

			$form = $this->_getForm();
			$form->setName('add_user');

			if( $form->fieldExists( 'password' ) ) {
				$form->removeField( 'password' );
			}


			$this->_form_add = $form;


		}

		return $this->_form_add;
	}

	/**
	 * @return bool
	 */
	public function catchAddForm()
	{
		return $this->catchForm( $this->getAddForm() );
	}


	/**
	 * @return array
	 */
	public static function getLocales()
	{
		$locales = [];

		foreach( Application_Admin::getSite()->getLocales() as $locale_str=>$locale ) {
			$locales[$locale_str] = $locale->getName();
		}

		return $locales;
	}


	/**
	 * @param string $password
	 */
	public function sendWelcomeEmail( $password )
	{
		$email = new Mailing_Email(
			'user_welcome',
			$this->getLocale(),
			Application_Admin::getSiteId()
		);

		$email->setVar('user', $this);
		$email->setVar('password', $password);

		$email->send( $this->getEmail() );
	}

}