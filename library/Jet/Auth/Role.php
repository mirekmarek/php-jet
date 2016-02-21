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
 * @JetApplication_Signals:signal = '/role/new'
 * @JetApplication_Signals:signal = '/role/updated'
 * @JetApplication_Signals:signal = '/role/deleted'
 *
 * @JetApplication_Signals:signal_object_class_name = 'Auth_Role_Signal'

 * @JetDataModel:name = 'role'
 * @JetDataModel:ID_class_name = 'DataModel_ID_Name'
 * @JetDataModel:database_table_name = 'Jet_Auth_Roles'
 */
class Auth_Role extends DataModel implements Auth_Role_Interface {

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
	 * @JetDataModel:form_field_label = 'Name'
	 * @JetDataModel:form_field_error_messages = [Form_Field_Abstract::ERROR_CODE_EMPTY=>'Please enter a name']
	 *
	 * @var string
	 */
	protected $name = '';

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
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = JET_AUTH_ROLE_PRIVILEGE_CLASS
	 * @JetDataModel:form_field_is_required = false
	 *
	 * @var Auth_Role_Privilege_Interface[]
	 */
	protected $privileges;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = JET_AUTH_USER_ROLES_CLASS
	 * @JetDataModel:form_field_type = false
	 *
	 * @var Auth_User_Interface[]
	 */
	protected $users;

    /**
     * @param string $ID
     *
     * @return Auth_Role_Interface
     */
    public static function get( $ID ) {
        return static::load( static::createIdObject($ID) );
    }


    /**
     * @return string
     */
    public function getID() {
        return $this->getIdObject()->toString();
    }

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
	 * @return Auth_User_Interface[]
	 */
	public function getUsers() {
		return $this->users;
	}


	/**
	 * @return Auth_Role_Privilege_Interface[]
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
			return [];
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
	 * @return Auth_Role_Interface[]
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
				$this->setPrivilege( $privilege, []);
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
	public function getForm( $form_name, array $properties_list ) {
		$this->_initPrivileges();

		return parent::getForm($form_name, $properties_list);
	}

	/**
	 *
	 */
	public function afterAdd() {
		$this->sendSignal('/role/new', ['role'=>$this]);
	}

	/**
	 *
	 */
	public function afterUpdate() {
		$this->sendSignal('/role/updated', ['role'=>$this]);
	}

	/**
	 *
	 */
	public function afterDelete() {
		$this->sendSignal('/role/deleted', ['role'=>$this]);
	}

}