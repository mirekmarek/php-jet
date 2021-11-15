<?php

namespace JetApplication;

use Jet\Auth_User_Interface;
use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_IDController_AutoIncrement;
use Jet\DataModel_Related_MtoN_Iterator;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_MultiSelect;
use Jet\Form_Field_RegistrationPassword;
use Jet\Form_Field_Select;
use Jet\Data_DateTime;
use Jet\Locale;
use Jet\Mailing_Email_Template;
use Jet\Tr;

/**
 *
 */
#[DataModel_Definition(
	name: 'user',
	database_table_name: 'users_administrators',
	id_controller_class: DataModel_IDController_AutoIncrement::class,
	id_controller_options: ['id_property_name' => 'id']
)]
class Auth_Administrator_User extends DataModel implements Auth_User_Interface
{

	/**
	 * @var int
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_ID_AUTOINCREMENT,
		is_id: true,
		form_field_type: false
	)]
	protected int $id = 0;

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 100,
		form_field_is_required: true,
		is_key: true,
		is_unique: true,
		form_field_label: 'Username',
		form_field_error_messages: [
			Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter username'
		]
	)]
	protected string $username = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		do_not_export: true,
		max_len: 255,
		form_field_is_required: true,
		is_key: true,
		form_field_type: Form::TYPE_REGISTRATION_PASSWORD,
		form_field_label: 'Password',
		form_field_options: ['password_confirmation_label' => 'Confirm password'],
		form_field_error_messages: [
			Form_Field_RegistrationPassword::ERROR_CODE_EMPTY           => 'Please enter password',
			Form_Field_RegistrationPassword::ERROR_CODE_CHECK_EMPTY     => 'Please enter confirm password',
			Form_Field_RegistrationPassword::ERROR_CODE_CHECK_NOT_MATCH => 'Passwords do not match'
		]
	)]
	protected string $password = '';

	/**
	 * @var bool
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_BOOL,
		default_value: false,
		form_field_type: false
	)]
	protected bool $is_superuser = false;

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
		form_field_label: 'E-mail',
		form_field_is_required: true,
		form_field_error_messages: [
		Form_Field_Input::ERROR_CODE_EMPTY          => 'Please enter e-mail address',
		Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Please enter e-mail address'
	])]
	protected string $email = '';

	/**
	 * @var ?Locale
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_LOCALE,
		form_field_label: 'Locale',
		form_field_is_required: true,
		form_field_error_messages: [
			Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select locale',
			Form_Field_Select::ERROR_CODE_EMPTY         => 'Please select locale'
		],
		form_field_get_select_options_callback: [
			self::class,
			'getLocales'
		],
	)]
	protected ?Locale $locale = null;

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 100,
		form_field_label: 'First name'
	)]
	protected string $first_name = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 100,
		form_field_label: 'Surname'
	)]
	protected string $surname = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 65536,
		form_field_label: 'Description'
	)]
	protected string $description = '';

	/**
	 * @var bool
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_BOOL,
		default_value: true,
		form_field_label: 'Password is valid'
	)]
	protected bool $password_is_valid = true;

	/**
	 * @var ?Data_DateTime
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE_TIME,
		default_value: null,
		form_field_label: 'Password is valid till',
		form_field_error_messages: [
			Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid date format'
		]
	)]
	protected ?Data_DateTime $password_is_valid_till = null;

	/**
	 * @var bool
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_BOOL,
		default_value: false,
		form_field_label: 'User is blocked'
	)]
	protected bool $user_is_blocked = false;

	/**
	 * @var ?Data_DateTime
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE_TIME,
		default_value: null,
		form_field_label: 'User is blocked till',
		form_field_error_messages: [
			Form_Field_Input::ERROR_CODE_INVALID_FORMAT => 'Invalid date format'
		]
	)]
	protected ?Data_DateTime $user_is_blocked_till = null;

	/**
	 * @var Auth_Administrator_User_Roles|DataModel_Related_MtoN_Iterator|Auth_Administrator_Role[]
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATA_MODEL,
		data_model_class: Auth_Administrator_User_Roles::class
	)]
	protected $roles;

	/**
	 * @var ?Form
	 */
	protected ?Form $_form_add = null;

	/**
	 * @var ?Form
	 */
	protected ?Form $_form_edit = null;


	/**
	 * @param string|null $username
	 * @param string|null $password
	 */
	public function __construct( ?string $username = null, ?string $password = null )
	{

		if( $username !== null ) {
			$this->setUsername( $username );
		}
		if( $password !== null ) {
			$this->setPassword( $password );
		}

		parent::__construct();
	}

	/**
	 * @param string $password
	 */
	public function setPassword( string $password ): void
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
	public function encryptPassword( string $password ): string
	{
		return password_hash( $password, PASSWORD_DEFAULT );
	}

	/**
	 * @param string|int $id
	 *
	 * @return static|null
	 */
	public static function get( string|int $id ): static|null
	{
		return static::load( $id );
	}

	/**
	 * @param string|null $role_id (optional)
	 * @param string $search
	 *
	 * @return Auth_Administrator_User[]
	 */
	public static function getList( string|null $role_id = null, string $search = '' ): iterable
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

			$search = '%' . $search . '%';
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
	 * @return static|null
	 */
	public static function getByIdentity( string $username, string $password ): static|null
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
			return null;
		}

		if( !$user->verifyPassword( $password ) ) {
			return null;
		}

		return $user;
	}

	/**
	 * @param string $plain_password
	 *
	 * @return bool
	 */
	public function verifyPassword( string $plain_password ): bool
	{
		return password_verify( $plain_password, $this->password );
	}

	/**
	 * @param string $username
	 *
	 * @return static|null;
	 */
	public static function getGetByUsername( string $username ): static|null
	{
		return static::load(
			[
				'username' => $username,
			]
		);
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername( string $username ): void
	{
		$this->username = $username;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail( string $email ): void
	{
		$this->email = $email;
	}

	/**
	 * @return Locale
	 */
	public function getLocale(): Locale
	{
		return $this->locale;
	}

	/**
	 * @param Locale|string $locale
	 */
	public function setLocale( Locale|string $locale ): void
	{
		if( !($locale instanceof Locale) ) {
			$locale = new Locale( $locale );
		}
		$this->locale = $locale;
	}


	/**
	 * @return string
	 */
	public function getFirstName(): string
	{
		return $this->first_name;
	}

	/**
	 * @param string $first_name
	 */
	public function setFirstName( string $first_name ): void
	{
		$this->first_name = $first_name;
	}

	/**
	 * @return string
	 */
	public function getSurname(): string
	{
		return $this->surname;
	}

	/**
	 * @param string $surname
	 */
	public function setSurname( string $surname ): void
	{
		$this->surname = $surname;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->first_name . ' ' . $this->surname;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription( string $description ): void
	{
		$this->description = $description;
	}

	/**
	 * @return bool
	 */
	public function getPasswordIsValid(): bool
	{
		return $this->password_is_valid;
	}

	/**
	 * @param bool $password_is_valid
	 */
	public function setPasswordIsValid( bool $password_is_valid ): void
	{
		$this->password_is_valid = $password_is_valid;
	}

	/**
	 *
	 * @return Data_DateTime|null
	 */
	public function getPasswordIsValidTill(): Data_DateTime|null
	{
		return $this->password_is_valid_till;
	}

	/**
	 * @param Data_DateTime|string|null $password_is_valid_till
	 */
	public function setPasswordIsValidTill( Data_DateTime|string|null $password_is_valid_till ): void
	{
		if( !$password_is_valid_till ) {
			$this->password_is_valid_till = null;
		} else {
			if( $password_is_valid_till instanceof Data_DateTime ) {
				$this->password_is_valid_till = $password_is_valid_till;
			} else {
				$this->password_is_valid_till = new Data_DateTime( $password_is_valid_till );
			}
		}

	}

	/**
	 * @return bool
	 */
	public function isBlocked(): bool
	{
		return $this->user_is_blocked;
	}

	/**
	 * @return null|Data_DateTime
	 */
	public function isBlockedTill(): null|Data_DateTime
	{
		return $this->user_is_blocked_till;
	}

	/**
	 * @param string|Data_DateTime|null $till
	 */
	public function block( string|Data_DateTime|null $till = null ): void
	{
		$this->user_is_blocked = true;
		if( !$till ) {
			$this->user_is_blocked_till = null;
		} else {
			if( $till instanceof Data_DateTime ) {
				$this->user_is_blocked_till = $till;
			} else {
				$this->user_is_blocked_till = new Data_DateTime( $till );
			}
		}
	}

	/**
	 *
	 */
	public function unBlock(): void
	{
		$this->user_is_blocked = false;
		$this->user_is_blocked_till = null;
	}


	/**
	 * @return Auth_Administrator_Role[]
	 */
	public function getRoles(): array
	{
		return $this->roles;
	}

	/**
	 * @param array $role_ids
	 */
	public function setRoles( array $role_ids ): void
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
	public function hasRole( string $role_id ): bool
	{
		foreach( $this->roles as $role ) {
			if( $role->getId() == $role_id ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $privilege
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function hasPrivilege( string $privilege, mixed $value=null ): bool
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
	public function getIsSuperuser(): bool
	{
		return $this->is_superuser;
	}

	/**
	 * @param bool $is_superuser
	 */
	public function setIsSuperuser( bool $is_superuser ): void
	{
		$this->is_superuser = $is_superuser;
	}

	/**
	 * @param string $privilege
	 *
	 * @return array
	 */
	public function getPrivilegeValues( string $privilege ): array
	{
		$result = [];
		foreach( $this->roles as $role ) {
			$result = array_merge(
				$role->getPrivilegeValues( $privilege ), $result
			);
		}

		return array_unique( $result );
	}

	/**
	 *
	 * @param string $username
	 *
	 * @return bool
	 */
	public function usernameExists( string $username ): bool
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
	public function resetPassword(): void
	{

		$password = static::generatePassword();

		$this->setPassword( $password );
		$this->setPasswordIsValid( false );
		$this->save();


		$email_template = new Mailing_Email_Template(
			'administrator/user_password_reset',
			locale: $this->getLocale()
		);

		$email_template->setVar( 'user', $this );
		$email_template->setVar( 'password', $password );

		$email = $email_template->getEmail();
		$email->setTo( $this->getEmail() );
		$email->send();

	}

	/**
	 * @return string
	 */
	public static function generatePassword(): string
	{
		srand();
		$password = '';
		$length = rand( 8, 12 );

		$chars = [
			0,
			1,
			2,
			3,
			4,
			5,
			6,
			7,
			8,
			9,
			'a',
			'b',
			'c',
			'd',
			'e',
			'f',
			'g',
			'h',
			'i',
			'j',
			'k',
			'l',
			'm',
			'n',
			'o',
			'p',
			'q',
			'r',
			's',
			't',
			'u',
			'v',
			'w',
			'x',
			'y',
			'z',
			'A',
			'B',
			'C',
			'D',
			'E',
			'F',
			'G',
			'H',
			'I',
			'J',
			'K',
			'L',
			'M',
			'N',
			'O',
			'P',
			'Q',
			'R',
			'S',
			'T',
			'U',
			'V',
			'W',
			'X',
			'Y',
			'Z',
			'@',
			'#',
			'$',
			'%',
			'*',
		];

		for( $l = 0; $l < $length; $l++ ) {
			$password .= $chars[rand( 1, count( $chars ) ) - 1];
		}

		return $password;
	}

	/**
	 * @param string $password
	 *
	 * @return bool
	 */
	public function verifyPasswordStrength( string $password ): bool
	{
		if( strlen( $password ) < 5 ) {
			return false;
		}

		return true;
	}


	/**
	 *
	 *
	 * @return Form
	 */
	public function _getForm(): Form
	{

		$form = $this->getCommonForm();

		$roles = new Form_Field_MultiSelect( 'roles', 'Roles', $this->roles );
		$roles->setSelectOptions( Auth_Administrator_Role::getList() );
		$roles->setCatcher( function( $value ) {
			$this->setRoles( $value );
		} );
		$roles->setErrorMessages( [
			Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE => "Please select role",
		] );
		$form->addField( $roles );


		if( $this->getIsNew() ) {
			/**
			 * @var Form_Field_RegistrationPassword $password_field
			 */
			$password_field = $form->getField( 'password' );
			$password_field->setIsRequired( true );
			$password_field->setPasswordStrengthCheckCallback( [
				$this,
				'verifyPasswordStrength'
			] );
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
							'Sorry, but username %USERNAME% is registered.', ['USERNAME' => $username]
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
	public function getRegistrationForm(): Form
	{
		$form = $this->_getForm();
		$form->setName( 'register_user' );

		foreach( $form->getFields() as $field ) {
			if( !in_array( $field->getName(), [
				'username',
				'locale',
				'password',
				'email'
			] ) ) {
				$form->removeField( $field->getName() );
			}
		}

		$form->getField( 'locale' )->setDefaultValue( Locale::getCurrentLocale() );

		/**
		 * @var Form_Field_RegistrationPassword $pwd
		 */
		$pwd = $form->getField( 'password' );
		$pwd->setPasswordConfirmationLabel( 'Confirm password' );

		return $form;
	}


	/**
	 *
	 * @return Form
	 */
	public function getEditForm(): Form
	{
		if( !$this->_form_edit ) {
			$form = $this->_getForm();
			$form->setName( '_user' );

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
	public function catchEditForm(): bool
	{
		return $this->getEditForm()->catch();
	}


	/**
	 * @return Form
	 */
	public function getAddForm(): Form
	{
		if( !$this->_form_add ) {

			$form = $this->_getForm();
			$form->setName( 'add_user' );

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
	public function catchAddForm(): bool
	{
		return $this->getAddForm()->catch();
	}


	/**
	 * @return array
	 */
	public static function getLocales(): array
	{
		$locales = [];

		foreach( Application_Admin::getBase()->getLocales() as $locale_str => $locale ) {
			$locales[$locale_str] = $locale->getName();
		}

		return $locales;
	}


	/**
	 * @param string $password
	 */
	public function sendWelcomeEmail( string $password ): void
	{
		$email_template = new Mailing_Email_Template(
			'administrator/user_welcome',
			locale: $this->getLocale()
		);

		$email_template->setVar( 'user', $this );
		$email_template->setVar( 'password', $password );

		$email = $email_template->getEmail();
		$email->setTo( $this->getEmail() );
		$email->send();

	}

}