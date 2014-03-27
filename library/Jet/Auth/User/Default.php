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
 * Class Auth_User_Default
 *
 * @JetDataModel:database_table_name = 'Jet_Auth_Users'
 */
class Auth_User_Default extends Auth_User_Abstract {

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:is_required = true
	 * @JetDataModel:is_key = true
	 * @JetDataModel:is_unique = true
	 * @JetDataModel:form_field_label = 'User name'
	 *
	 * @var string
	 */
	protected $login = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:do_not_serialize = true
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:is_required = true
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
	 * @JetDataModel:type = Jet\DataModel::TYPE_BOOL
	 * @JetDataModel:default_value = false
	 * @JetDataModel:form_field_type = false
	 *
	 * @var bool
	 */
	protected $is_superuser = false;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'E-mail'
	 *
	 * @var string
	 */
	protected $email = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_LOCALE
	 * @JetDataModel:form_field_label = 'Locale'
	 * @JetDataModel:form_field_get_select_options_callback = [ 'Jet\Mvc','getAllSitesLocalesList']
	 *
	 * @var Locale
	 */
	protected $locale;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:form_field_label = 'First name'
	 *
	 * @var string
	 */
	protected $first_name = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 100
	 * @JetDataModel:form_field_label = 'Surname'
	 *
	 * @var string
	 */
	protected $surname = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 65536
	 * @JetDataModel:form_field_label = 'Description'
	 *
	 * @var string
	 */
	protected $description = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_BOOL
	 * @JetDataModel:default_value = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var bool
	 */
	protected $password_is_valid = true;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATE_TIME
	 * @JetDataModel:default_value = NULL
	 * @JetDataModel:form_field_type = false
	 *
	 * @var DateTime
	 */
	protected $password_is_valid_till;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_BOOL
	 * @JetDataModel:default_value = false
	 * @JetDataModel:form_field_type = false
	 *
	 * @var bool
	 */
	protected $user_is_blocked = false;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATE_TIME
	 * @JetDataModel:default_value = NULL
	 * @JetDataModel:form_field_type = false
	 *
	 * @var DateTime
	 */
	protected $user_is_blocked_till;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_BOOL
	 * @JetDataModel:default_value = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var bool
	 */
	protected $user_is_activated = true;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:default_value = ''
	 * @JetDataModel:form_field_type = false
	 *
	 * @var string
	 */
	protected $user_activation_hash = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Jet\Auth_User_Roles'
	 * @JetDataModel:form_field_type = 'MultiSelect'
	 * @JetDataModel:form_field_label = 'Roles'
	 * @JetDataModel:form_field_get_select_options_callback = ['Jet\Auth', 'getRolesList']
	 *
	 * @var Auth_User_Roles
	 */
	protected $roles;


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
			$q = array(
				'this.login' => $login
			);
		} else {
			$q = array(
				'this.login' => $login,
				'AND',
				'this.ID!=' => $this->ID
			);
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
	 * @return DateTime
	 */
	public function getPasswordIsValidTill() {
		return $this->password_is_valid_till;
	}

	/**
	 * @param DateTime|string $password_is_valid_till
	 *
	 * @return void
	 */
	public function setPasswordIsValidTill( $password_is_valid_till ) {
		if(!$password_is_valid_till) {
			$this->password_is_valid_till = null;
		} else {
			$this->password_is_valid_till = new DateTime($password_is_valid_till);
		}
	}

	/**
	 * @return bool
	 */
	public function getIsBlocked() {
		return $this->user_is_blocked;
	}

	/**
	 * @return null|DateTime
	 */
	public function getIsBlockedTill() {
		return $this->user_is_blocked_till;
	}

	/**
	 * @param string|DateTime|null $till
	 */
	public function block( $till=null ) {
		$this->user_is_blocked = true;
		if( !$till ) {
			$this->user_is_blocked_till = null;
		} else {
			$this->user_is_blocked_till = new DateTime($till);
		}
	}

	/**
	 *
	 */
	public function unBlock() {
		$this->user_is_blocked = false;
		$this->user_is_blocked_till = null;
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
	 * @return Auth_User_Abstract[]
	 */
	public function getUsersList( $role_ID=null ) {
		if($role_ID) {
			$query = array(
				'Auth_Role_Default.ID' => $role_ID
			);
		} else {
			$query = array();
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
		$query = array();

		if($role_ID) {
			$query['Auth_Role_Default.ID'] = $role_ID;
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
	 * @return Auth_User_Abstract|null
	 */
	public function getByIdentity(  $login, $password  ) {

		return $this->fetchOneObject( array(
			'this.login' => $login,
			'AND',
			'this.password' => $this->encryptPassword($password)
		));
	}


	/**
	 * @param string $login
	 *
	 * @return null|Auth_User_Abstract|DataModel
	 */
	public function getGetByLogin(  $login  ) {

		return $this->fetchOneObject( array(
			'this.login' => $login
		) );
	}

	/**
	 * @abstract
	 * @return Auth_Role_Abstract[]
	 */
	public function getRoles() {
		return $this->roles;
	}

	/**
	 * @param array $roles_IDs
	 */
	public function setRoles( array $roles_IDs ) {
		$roles = array();

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
			 * @var Auth_Role_Abstract $role
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
			 * @var Auth_Role_Abstract $role
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
		$result = array();
		foreach($this->roles as $role) {
			/**
			 * @var Auth_Role_Abstract $role
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

		$form = $this->getForm($form_name, array('login', 'password', 'email'));

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

			/** @var $user Auth_User_Default */
			if($user->getLoginExists( $login )) {
				$field->setErrorMessage(
					Tr::_(
						'Sorry, but username %LOGIN% is registered.',
						array('LOGIN'=>$login)
					)
				);
				return false;
			}
			return true;
		});

	}
}