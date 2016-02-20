<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Auth
 * @subpackage Auth_User
 */
namespace Jet;

/**
 *
 * @JetApplication_Signals:signal = '/user/new'
 * @JetApplication_Signals:signal = '/user/deleted'
 * @JetApplication_Signals:signal = '/user/updated'
 * @JetApplication_Signals:signal = '/user/blocked'
 * @JetApplication_Signals:signal = '/user/unblocked'
 * @JetApplication_Signals:signal = '/user/activated'
 *
 * @JetApplication_Signals:signal_object_class_name = 'Auth_User_Signal'
 *
 * @JetDataModel:name = 'user'
 * @JetDataModel:ID_class_name = 'DataModel_ID_UniqueString'
 * @JetDataModel:database_table_name = 'Jet_Auth_Users'
 */
class Auth_User extends DataModel implements Auth_User_Interface {

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:is_key = true
	 * @JetDataModel:is_unique = true
	 * @JetDataModel:form_field_label = 'User name'
	 *
	 * @var string
	 */
	protected $login = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:do_not_export = true
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:form_field_is_required = true
	 * @JetDataModel:is_key = true
	 * @JetDataModel:form_field_type = 'Password'
	 * @JetDataModel:form_field_label = 'Password'
	 * @JetDataModel:form_field_options = ['password_check_label'=>'Confirm password']
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
	 *
	 * @var string
	 */
	protected $email = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_LOCALE
	 * @JetDataModel:form_field_label = 'Locale'
	 * @JetDataModel:form_field_get_select_options_callback = [ 'Mvc_Site','getAllLocalesList']
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
	 * @JetDataModel:form_field_type = false
	 *
	 * @var bool
	 */
	protected $password_is_valid = true;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATE_TIME
	 * @JetDataModel:default_value = null
	 * @JetDataModel:form_field_type = false
	 *
	 * @var Data_DateTime
	 */
	protected $password_is_valid_till;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 * @JetDataModel:default_value = false
	 * @JetDataModel:form_field_type = false
	 *
	 * @var bool
	 */
	protected $user_is_blocked = false;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATE_TIME
	 * @JetDataModel:default_value = null
	 * @JetDataModel:form_field_type = false
	 *
	 * @var Data_DateTime
	 */
	protected $user_is_blocked_till;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 * @JetDataModel:default_value = true
	 * @JetDataModel:form_field_type = false
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
	 * @JetDataModel:data_model_class = JET_AUTH_USER_ROLES_CLASS
	 * @JetDataModel:form_field_creator_method_name = 'createRolesFormField'
	 * @JetDataModel:form_field_type = Form::TYPE_MULTI_SELECT
	 * @JetDataModel:form_field_label = 'Roles'
	 * @JetDataModel:form_field_get_select_options_callback = ['Auth', 'getRolesList']
	 * @JetDataModel:form_catch_value_method_name = 'setRoles'
	 *
	 * @var Auth_User_Roles|DataModel_Related_MtoN_Iterator
	 */
	protected $roles;

	/**
	 * @param string|null $login
	 * @param string|null $password
	 */
	public function __construct( $login=null, $password=null ) {

		if($login!==null) {
			$this->setLogin($login);
		}
		if($password!==null) {
			$this->setPassword($password);
		}

		parent::__construct();
	}

	/**
	 * @return string
	 */
	public function getLogin() {
		return $this->login;
	}

	/**
	 * @param string $login
	 */
	public function setLogin( $login ) {
		$this->login = $login;
	}

	/**
	 *
	 * @param $login
	 *
	 * @return bool
	 */
	public function getLoginExists( $login ) {
		if($this->getIsNew()) {
			$q = [
				'this.login' => $login
			];
		} else {
			$q = [
				'this.login' => $login,
				'AND',
				'this.ID!=' => $this->ID
			];
		}
		return (bool)$this->getBackendInstance()->getCount( $this->createQuery( $q ) );
	}


	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword( $password ) {
		if($password) {
			$this->password = $this->encryptPassword($password);
		}
	}

	/**
	 * @param string $password
	 * @return string
	 */
	public function encryptPassword( $password ) {
		return md5($password);
	}

	/**
	 * @return boolean
	 */
	public function getIsSuperuser() {
		return $this->is_superuser;
	}

	/**
	 * @param boolean $is_superuser
	 */
	public function setIsSuperuser($is_superuser) {
		$this->is_superuser = (bool)$is_superuser;
	}

	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail( $email ) {
		$this->email = $email;
	}

	/**
	 * @return Locale
	 */
	public function getLocale() {
		return $this->locale;
	}

	/**
	 * @param string|Locale $locale
	 */
	public function setLocale( $locale ) {
		if( !($locale instanceof Locale) ) {
			$locale = new Locale($locale);
		}
		$this->locale = $locale;
	}

	/**
	 * @return string
	 */
	public function getFirstName() {
		return $this->first_name;
	}

	/**
	 * @param string $first_name
	 */
	public function setFirstName( $first_name ) {
		$this->first_name = $first_name;
	}

	/**
	 * @return string
	 */
	public function getSurname() {
		return $this->surname;
	}

	/**
	 * @param string $surname
	 */
	public function setSurname($surname) {
		$this->surname = $surname;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->first_name.' '.$this->surname;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return bool
	 */
	public function getPasswordIsValid() {
		return $this->password_is_valid;
	}

	/**
	 * @param bool $password_is_valid
	 */
	public function setPasswordIsValid($password_is_valid) {
		$this->password_is_valid = (bool)$password_is_valid;
	}

	/**
	 *
	 * @return Data_DateTime
	 */
	public function getPasswordIsValidTill() {
		return $this->password_is_valid_till;
	}

	/**
	 * @param Data_DateTime|string $password_is_valid_till
	 *
	 * @return void
	 */
	public function setPasswordIsValidTill( $password_is_valid_till ) {
		if(!$password_is_valid_till) {
			$this->password_is_valid_till = null;
		} else {
			$this->password_is_valid_till = new Data_DateTime($password_is_valid_till);
		}
	}

	/**
	 * @return bool
	 */
	public function getIsBlocked() {
		return $this->user_is_blocked;
	}

	/**
	 * @return null|Data_DateTime
	 */
	public function getIsBlockedTill() {
		return $this->user_is_blocked_till;
	}

	/**
	 * @param string|Data_DateTime|null $till
	 */
	public function block( $till=null ) {
		$this->user_is_blocked = true;
		if( !$till ) {
			$this->user_is_blocked_till = null;
		} else {
			$this->user_is_blocked_till = new Data_DateTime($till);
		}

		$this->sendSignal('/user/blocked', ['user'=>$this]);

	}

	/**
	 *
	 */
	public function unBlock() {
		$this->user_is_blocked = false;
		$this->user_is_blocked_till = null;

		$this->sendSignal('/user/unblocked', ['user'=>$this]);
	}

	/**
	 * @return bool
	 */
	public function getIsActivated() {
		return $this->user_is_activated;
	}

	/**
	 * @param string|null $user_activation_hash (optional)
	 * @return bool
	 */
	public function activate( $user_activation_hash=null ) {
		if(
			$user_activation_hash &&
			$this->user_activation_hash!=$user_activation_hash
		) {
			return false;
		}
		$this->user_is_activated = true;

		$this->sendSignal('/user/activated', ['user'=>$this]);
		return true;
	}

	/**
	 * @return string
	 */
	public function getActivationHash() {
		return $this->user_activation_hash;
	}

	/**
	 * @param string $user_activation_hash
	 */
	public function setActivationHash($user_activation_hash) {
		$this->user_activation_hash = $user_activation_hash;
	}

	/**
	 * @param string|null $role_ID (optional)
	 * @return Auth_User_Interface[]
	 */
	public function getUsersList( $role_ID=null ) {
		if($role_ID) {
			$query = [
				'Auth_Role.ID' => $role_ID
			];
		} else {
			$query = [];
		}

		$list = $this->fetchObjects( $query );
		$list->getQuery()->setOrderBy('login');

		return $list;

	}

	/**
	 * @param string|null $role_ID
	 *
	 * @return DataModel_Fetch_Data_Assoc
	 */
	public function getUsersListAsData( $role_ID=null ) {
		$query = [];

		if($role_ID) {
			$query['role.ID'] = $role_ID;
		}

		$properties = $this->getDataModelDefinition()->getProperties();
		unset($properties['password']);

		$list = $this->fetchDataAssoc( $properties, $query );
		$list->getQuery()->setOrderBy('login');

		return $list;
	}

	/**
	 * @param string $login
	 * @param string $password
	 * @return Auth_User_Interface|null
	 */
	public function getByIdentity(  $login, $password  ) {

		return $this->fetchOneObject( [
			'this.login' => $login,
			'AND',
			'this.password' => $this->encryptPassword($password)
		]);
	}


	/**
	 * @param string $login
	 *
	 * @return null|Auth_User_Interface|DataModel
	 */
	public function getGetByLogin(  $login  ) {

		return $this->fetchOneObject( [
			'this.login' => $login
		]);
	}


	/**
	 * @param DataModel_Definition_Property_Abstract $property_definition
	 *
	 * @return Form_Field_Abstract
	 */
	public function createRolesFormField( DataModel_Definition_Property_Abstract $property_definition ) {

		return $property_definition->createFormField( $this->roles );
	}

	/**
	 * @abstract
	 * @return Auth_Role_Interface[]
	 */
	public function getRoles() {
		return $this->roles;
	}

	/**
	 * @param array $roles_IDs
	 */
	public function setRoles( array $roles_IDs ) {
		$roles = [];

		foreach($roles_IDs as $role_ID) {
			$role = Auth::getRole($role_ID);
			if(!$role) {
				continue;
			}
			$roles[] = $role;
		}
		$this->roles->setItems( $roles );

	}

	/**
	 * @param string $role_ID
	 *
	 * @return bool
	 */
	public function getHasRole( $role_ID ) {
		foreach($this->roles as $role) {
			/**
			 * @var Auth_Role_Interface $role
			 */
			if( $role->getID()==$role_ID ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $privilege
	 * @param mixed $value
	 * @return bool
	 */
	public function getHasPrivilege( $privilege, $value ) {
		if($this->getIsSuperuser()) {
			return true;
		}

		foreach($this->roles as $role) {
			/**
			 * @var Auth_Role_Interface $role
			 */
			if($role->getHasPrivilege( $privilege, $value )) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $privilege
	 *
	 * @return array
	 */
	public function getPrivilegeValues($privilege) {
		$result = [];
		foreach($this->roles as $role) {
			/**
			 * @var Auth_Role_Interface $role
			 */

			$result = array_merge(
				$role->getPrivilegeValues($privilege),
				$result
			);
		}

		$result = array_unique($result);

		return $result;
	}


	/**
	 * @param string $form_name
	 *
	 * @return Form
	 */
	public function getCommonForm( $form_name='' ) {
		$form = parent::getCommonForm( $form_name );

		$this->_setupForm($form);

		return $form;
	}


	/**
	 * @param string $form_name
	 *
	 * @return Form
	 */
	public function getSimpleForm( $form_name='' ) {

		if(!$form_name) {
			$definition = $this->getDataModelDefinition();
			$form_name = $definition->getModelName();

		}

		$form = $this->getForm($form_name, ['login', 'password', 'email']);

		$this->_setupForm($form);

		return $form;

	}

	/**
	 * @param Form $form
	 */
	protected function _setupForm( Form $form ) {
		if( $this->getIsNew() ) {
			$form->getField('password')->setIsRequired(true);
		} else {
			$form->getField('password')->setIsRequired(false);
		}

		$user = $this;

		$form->getField('login')->setValidateDataCallback(function( Form_Field_Abstract $field ) use ($user) {
			$login = $field->getValue();

			/** @var $user Auth_User */
			if($user->getLoginExists( $login )) {
				$field->setErrorMessage(
					Tr::_(
						'Sorry, but username %LOGIN% is registered.',
						['LOGIN'=>$login]
					)
				);
				return false;
			}
			return true;
		});

	}


	/**
	 *
	 */
	public function afterAdd() {
		$this->sendSignal('/user/new', ['user'=>$this]);
	}

	/**
	 *
	 */
	public function afterUpdate() {
		$this->sendSignal('/user/updated', ['user'=>$this]);
	}

	/**
	 *
	 */
	public function afterDelete() {
		$this->sendSignal('/user/deleted', ['user'=>$this]);
	}

}