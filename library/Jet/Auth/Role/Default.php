<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Auth
 * @subpackage Auth_Role
 */
namespace Jet;

/**
 * Class Auth_Role_Default
 *
 * @JetDataModel:database_table_name = 'Jet_Auth_Roles'
 * @JetDataModel:ID_class_name = 'Jet\DataModel_ID_Name'
 */
class Auth_Role_Default extends Auth_Role_Abstract {

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
	 * @JetDataModel:form_field_label = 'Name'
	 * @JetDataModel:form_field_error_messages = ['empty'=>'Please enter a name']
	 *
	 * @var string
	 */
	protected $name = '';

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
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Jet\Auth_Role_Privilege_Default'
	 * @JetDataModel:is_required = false
	 *
	 * @var Auth_Role_Privilege_Abstract[]
	 */
	protected $privileges;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Jet\Auth_User_Roles'
	 * @JetDataModel:form_field_type = false
	 *
	 * @var Auth_User_Abstract[]
	 */
	protected $users;

	/**
	 * @return string
	 */
	public function toString() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function  __toString() {
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
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
	 * @return Auth_User_Abstract[]
	 */
	public function getUsers() {
		return $this->users;
	}


	/**
	 * @return Auth_Role_Privilege_Abstract[]
	 */
	public function getPrivileges() {
		return $this->privileges;
	}

	/**
	 * Returns privilege values or empty array if the role does not have the privilege
	 *
	 * @param string $privilege
	 * @return array
	 */
	public function getPrivilegeValues( $privilege ) {
		if(!isset($this->privileges[$privilege])) {
			return array();
		} else {
			return $this->privileges[$privilege]->getValues();
		}
	}

	/**
	 * Data format:
	 *
	 * array(
	 *      'privilege' => array('value1', 'value2')
	 * )
	 *
	 * @param array $privileges
	 */
	public function setPrivileges(array $privileges) {
		/** @noinspection PhpUndefinedMethodInspection */
		$this->privileges->clearData();

		foreach($privileges as $privilege=>$values) {
			$this->setPrivilege($privilege, $values);
		}
	}

	/**
	 * Example:
	 *
	 * privilege: save_object
	 * values: object_ID_1,object_ID_2, object_ID_N
	 *
	 *
	 * @param string $privilege
	 * @param array $values
	 */
	public function setPrivilege( $privilege, array $values ) {
		if(!isset($this->privileges[$privilege])) {
			$this->privileges[$privilege] = Auth_Factory::getPrivilegeInstance( $privilege, $values );
		} else {
			$this->privileges[$privilege]->setValues( $values );
		}
	}

	/**
	 * @param string $privilege
	 */
	public function removePrivilege( $privilege ) {
		if( isset($this->privileges[$privilege]) ) {
			unset( $this->privileges[$privilege] );
		}
	}

	/**
	 * @param string $privilege
	 * @param mixed $value
	 * @return bool
	 */
	public function getHasPrivilege( $privilege, $value ) {
		if( !isset($this->privileges[$privilege]) ) {
			return false;
		}

		return $this->privileges[$privilege]->getHasValue($value);
	}

	/**
	 * @return Auth_Role_Abstract[]
	 */
	public function getRolesList() {
		$list = $this->fetchObjects();
		$list->getQuery()->setOrderBy('name');
		return $list;
	}

	/**
	 * @return DataModel_Fetch_Data_Assoc
	 */
	public function getRolesListAsData() {
		$list = $this->fetchDataAssoc( $this->getDataModelDefinition()->getProperties() );
		$list->getQuery()->setOrderBy('name');
		return $list;
	}


	/**
	 *
	 */
	protected function _initPrivileges() {
		$available_privileges_list = Auth::getAvailablePrivilegesList();

		foreach( $available_privileges_list as $privilege=>$privilege_data ) {
			if(!isset($this->privileges[$privilege])) {
				$this->setPrivilege( $privilege, array() );
			}
		}

	}

	/**
	 *
	 * @param string $form_name
	 * @param array $properties_list
	 *
	 * @throws DataModel_Exception
	 * @return Form
	 */
	protected function getForm( $form_name, array $properties_list ) {
		$this->_initPrivileges();

		return parent::getForm($form_name, $properties_list);
	}
}