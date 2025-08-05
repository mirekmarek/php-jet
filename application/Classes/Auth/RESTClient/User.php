<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Auth_User_Interface;
use Jet\DataModel;
use Jet\DataModel_Definition;
use Jet\DataModel_Fetch_Instances;
use Jet\DataModel_IDController_AutoIncrement;
use Jet\Form;
use Jet\Form_Definition;
use Jet\Form_Field_Input;
use Jet\Form_Field;
use Jet\Data_DateTime;
use Jet\Form_Field_Password;
use Jet\Locale;
use Jet\Mailing_Email_Template;

/**
 *
 */
#[DataModel_Definition(
	name: 'user',
	database_table_name: 'users_rest_clients',
	id_controller_class: DataModel_IDController_AutoIncrement::class,
	id_controller_options: ['id_property_name' => 'id']
)]
class Auth_RESTClient_User extends DataModel implements Auth_User_Interface
{

	/**
	 * @var int
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_ID_AUTOINCREMENT,
		is_id: true,
	)]
	protected int $id = 0;

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		is_key: true,
		max_len: 100,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_INPUT,
		is_required: true,
		label: 'Username',
		error_messages: [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter username',
			'exists' => 'Sorry, but username %USERNAME% is registered.'
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
		is_key: true,
	)]
	protected string $password = '';

	/**
	 * @var string
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_STRING,
		max_len: 255,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_EMAIL,
		label: 'E-mail',
		is_required: true,
		error_messages: [
			Form_Field::ERROR_CODE_EMPTY          => 'Please enter e-mail address',
			Form_Field::ERROR_CODE_INVALID_FORMAT => 'Please enter e-mail address'
		]
	)]
	protected string $email = '';

	/**
	 * @var ?Locale
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_LOCALE,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_SELECT,
		label: 'Locale',
		is_required: true,
		error_messages: [
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select locale',
			Form_Field::ERROR_CODE_EMPTY         => 'Please select locale'
		],
		select_options_creator: [
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
		max_len: 65536,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_TEXTAREA,
		label: 'Description',
	)]
	protected string $description = '';

	/**
	 * @var bool
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_BOOL,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_CHECKBOX,
		label: 'User is blocked',
	)]
	protected bool $user_is_blocked = false;

	/**
	 * @var ?Data_DateTime
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATE_TIME,
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_DATE_TIME,
		label: 'User is blocked till',
		error_messages: [
			Form_Field::ERROR_CODE_INVALID_FORMAT => 'Invalid date format'
		]
	)]
	protected ?Data_DateTime $user_is_blocked_till = null;


	/**
	 * @var Auth_RESTClient_User_Roles[]
	 */
	#[DataModel_Definition(
		type: DataModel::TYPE_DATA_MODEL,
		data_model_class: Auth_RESTClient_User_Roles::class
	)]
	#[Form_Definition(
		type: Form_Field::TYPE_MULTI_SELECT,
		default_value_getter_name: 'getRoleIds',
		label: 'Roles',
		select_options_creator: [
			Auth_RESTClient_Role::class,
			'getList'
		],
		error_messages: [
			Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select role',
		]
	)]
	protected array $roles = [];


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
	 * @param bool $encrypt_password
	 */
	public function setPassword( string $password, bool $encrypt_password=true ): void
	{
		if( $password ) {
			$this->password = $encrypt_password ? $this->encryptPassword( $password ) : $password;
		}
	}

	/**
	 * @param string $plain_password
	 *
	 * @return string
	 */
	public function encryptPassword( string $plain_password ): string
	{
		return password_hash( $plain_password, PASSWORD_DEFAULT );
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
	 * @return Auth_RESTClient_User[]|DataModel_Fetch_Instances
	 */
	public static function getList( string|null $role_id = null, string $search = '' ): iterable|DataModel_Fetch_Instances
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
				'username *' => $search,
				'OR',
				'email *'    => $search,
			];
		}


		$list = static::fetchInstances( $where );
		$list->setLoadFilter(
			[
				'id',
				'username',
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
	 * @return static|null
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
	 * @return ?Locale
	 */
	public function getLocale(): ?Locale
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
	public function getName(): string
	{
		return $this->username;
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
	public function block( string|Data_DateTime|null $till = null ) : void
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
	public function unBlock() : void
	{
		$this->user_is_blocked = false;
		$this->user_is_blocked_till = null;
	}


	/**
	 * @return array<string,Auth_RESTClient_Role>
	 */
	public function getRoles(): array
	{
		$roles = [];

		foreach($this->roles as $r) {
			$role = $r->getRole();
			if($role) {
				$roles[$role->getId()] = $role;
			}
		}

		return $roles;
	}
	
	
	/**
	 * @return array<string>
	 */
	public function getRoleIds() : array
	{
		return array_keys($this->getRoles());
	}
	

	/**
	 * @param array<string> $role_ids
	 */
	public function setRoles( array $role_ids ): void
	{
		foreach($this->roles as $r) {
			if(!in_array($r->getRoleId(), $role_ids)) {
				if($r->getIsSaved()) {
					$r->delete();
				}
				unset($this->roles[$r->getRoleId()]);
			}
		}

		foreach( $role_ids as $role_id ) {

			$role = Auth_RESTClient_Role::get( $role_id );

			if( !$role ) {
				continue;
			}

			if(!isset($this->roles[$role->getId()])) {
				$new_item = new Auth_RESTClient_User_Roles();
				$new_item->setUserId($this->getId());
				$new_item->setRoleId($role->getId());

				$this->roles[$role->getId()] = $new_item;
				if($this->getIsSaved()) {
					$new_item->save();
				}
			}
		}

	}

	/**
	 * @param string $role_id
	 *
	 * @return bool
	 */
	public function hasRole( string $role_id ): bool
	{
		return isset($this->roles[$role_id]);
	}

	/**
	 * @param string $privilege
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function hasPrivilege( string $privilege, mixed $value=null ): bool
	{

		foreach( $this->roles as $role ) {
			if( $role->getRole()?->hasPrivilege( $privilege, $value ) ) {
				return true;
			}
		}

		return false;
	}


	/**
	 * @param string $privilege
	 *
	 * @return array<string|int|float>
	 */
	public function getPrivilegeValues( string $privilege ): array
	{
		$result = [];
		foreach( $this->roles as $role ) {
			$result = array_merge(
				$role->getRole()->getPrivilegeValues( $privilege ), $result
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
	public static function usernameExists( string $username ): bool
	{
		return (bool)static::getBackendInstance()->getCount( static::createQuery( [
			'username' => $username,
		] ) );
	}


	/**
	 *
	 */
	public function resetPassword(): void
	{

		$password = static::generatePassword();

		$this->setPassword( $password );
		$this->save();


		$email_template = new Mailing_Email_Template(
			template_id: 'rest-client/user_password_reset',
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
	public static function generatePassword() : string
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

		$form = $this->createForm('user_edit');
		
		$form->getField( 'username' )->setValidator(
			function( Form_Field_Input $field ) {
				$username = $field->getValue();

				if(
					!$this->getIsNew() &&
					$this->username==$username
				) {
					return true;
				}

				if( static::usernameExists( $username ) ) {
					$field->setError('exists', ['USERNAME' => $username]);

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
				'email'
			] ) ) {
				$form->removeField( $field->getName() );
			}
		}
		
		$form->getField( 'locale' )->setDefaultValue( Locale::getCurrentLocale() );
		
		$pwd = new Form_Field_Password( name: 'password', label: 'Password' );
		$pwd->setIsRequired( true );
		$pwd->setErrorMessages([
			Form_Field::ERROR_CODE_EMPTY           => 'Please enter password',
		]);
		
		$pwd->setFieldValueCatcher(function( $value) {
			$this->setPassword($value);
		});
		$form->addField($pwd);
		
		$pwd_check = $pwd->generateCheckField(
			field_name: 'password_check',
			field_label: 'Confirm password',
			error_message_empty: 'Please enter confirm password',
			error_message_not_match: 'Passwords do not match'
		);
		$form->addField($pwd_check);
		
		
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
	 * @param bool $get_as_string
	 * @return array<string,string|Locale>
	 */
	public static function getLocales( bool $get_as_string=true ): array
	{
		$locales = [];

		foreach( Application_REST::getBase()->getLocales() as $locale_str => $locale ) {
			if($get_as_string) {
				$locales[$locale_str] = $locale->getName();
			} else {
				$locales[$locale_str] = $locale;
			}
		}

		return $locales;
	}


	/**
	 * @param string $password
	 */
	public function sendWelcomeEmail( string $password ): void
	{
		$email_template = new Mailing_Email_Template(
			template_id: 'rest-client/user_welcome',
			locale: $this->getLocale()
		);

		$email_template->setVar( 'user', $this );
		$email_template->setVar( 'password', $password );

		$email = $email_template->getEmail();
		$email->setTo( $this->getEmail() );
		$email->send();

	}
	
	public function getUserIsBlocked(): bool
	{
		return $this->user_is_blocked;
	}
	
	public function setUserIsBlocked( bool $user_is_blocked ): void
	{
		$this->user_is_blocked = $user_is_blocked;
	}
	
	public function getUserIsBlockedTill(): ?Data_DateTime
	{
		return $this->user_is_blocked_till;
	}
	
	public function setUserIsBlockedTill( ?Data_DateTime $user_is_blocked_till ): void
	{
		$this->user_is_blocked_till = $user_is_blocked_till;
	}
	
}