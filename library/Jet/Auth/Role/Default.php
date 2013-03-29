<?php
/**
 *
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Auth
 * @subpackage Auth_Role
 */
namespace Jet;

class Auth_Role_Default extends Auth_Role_Abstract {
	/**
	 * @var array
	 */
	protected static $__data_model_properties_definition = array(
		"name" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 100,
			"is_required" => true
		),
		"description" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 65536,
		),
		"privileges" => array(
			"type" => self::TYPE_DATA_MODEL,
			"data_model_class" => "Jet\\Auth_Role_Privilege_Default",
			"is_required" => false,
			"form_field_type" => false
		),
		"users" => array(
			"type" => self::TYPE_DATA_MODEL,
			"data_model_class" => "Jet\\Auth_User_Roles",
			"form_field_type" => false
		)

	);

	/**
	 * @var string
	 */
	protected $ID = "";
	/**
	 * @var string
	 */
	protected $name = "";
	/**
	 * @var string
	 */
	protected $description = "";

	/**
	 * @var Auth_Role_Privilege_Abstract[]
	 */
	protected $privileges = array();

	/**
	 * @var Auth_User_Abstract[]
	 */
	protected $users = array();



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
	 * @param $name
	 * @param string $ID (optional)
	 */
	public function initNew( $name, $ID="" ) {
		$this->name = $name;

		if(!$ID) {
			$this->ID = $this->getEmptyIDInstance()->generateID($name, array($this, "getIDExists") );
		} else {
			$this->ID = $ID;
		}
	}

	/**
	 * @param string $ID
	 * @return bool
	 */
	public function getIDExists( $ID ) {
		$this->ID = $ID;

		return $this->_getIDExists();
	}

	/**
	 * @param bool $called_after_save
	 * @param null|mixed $backend_save_result
	 */
	protected  function generateID(  $called_after_save = false, $backend_save_result = null  ) {
		if(
			$this->name &&
			!$this->ID
		) {
			$this->ID = $this->getEmptyIDInstance()->generateID($this->name, array($this, "getIDExists") );
		}
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
	 * <code>
	 * array(
	 *      "privilege" => array("value1", "value2")
	 * )
	 * </code>
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
			$this->privileges[$privilege] = Auth_Factory::getPrivilegeInstance();
			$this->privileges[$privilege]->initNew( $privilege, $values );
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
		$list->getQuery()->setOrderBy("name");
		return $list;
	}

	/**
	 * @return DataModel_Fetch_Data_Assoc
	 */
	public function getRolesListAsData() {
		$list = $this->fetchDataAssoc( $this->getDataModelDefinition()->getProperties() );
		$list->getQuery()->setOrderBy("name");
		return $list;
	}

	/**
	 * @param string $form_name
	 *
	 * @return Form
	 */
	public function getCommonForm( $form_name="" ) {
		$form = parent::getCommonForm($form_name);

		$available_privileges_list = Auth::getAvailablePrivilegesList(true);

		$role = $this;
		foreach( $available_privileges_list as $privilege=>$privilege_data ) {
			$name = "/privileges/{$privilege}/values";
			$field = Form_Factory::getFieldInstance("MultiSelect", $name, $privilege_data->getLabel());

			/**
			 * @var Form_Field_MultiSelect $field
			 */
			$field->setSelectOptions( $privilege_data->getValuesList() );

			$field->setCatchDataCallback(function( $values ) use ($role, $privilege) {
				if(!$values) {
					$values = array();
				}
				/**
				 * @var Auth_Role_Abstract $role
				 */
				$role->setPrivilege( $privilege, $values );
			});

			$field->setDefaultValue($this->getPrivilegeValues( $privilege ));

			$form->setField( $name, $field );
		}

		return $form;
	}}