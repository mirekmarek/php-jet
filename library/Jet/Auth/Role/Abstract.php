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
 * Class Auth_Role_Abstract
 *
 * @JetFactory:class = 'Jet\\Auth_Factory'
 * @JetFactory:method = 'getRoleInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\\Auth_Role_Abstract'
 *
 * @JetDataModel:name = 'Role'
 * @JetDataModel:ID_class_name = 'Jet\\Auth_Role_ID_Abstract'
 */
abstract class Auth_Role_Abstract extends DataModel {

	/**
	 * @return string
	 */
	abstract public function toString();

	/**
	 * @return string
	 */
	public function  __toString() {
		return $this->toString();
	}

	/**
	 * @return string
	 */
	abstract public function getName();

	/**
	 * @param string $name
	 */
	abstract public function setName($name);

	/**
	 * @return string
	 */
	abstract public function getDescription();

	/**
	 * @param string $description
	 */
	abstract public function setDescription($description);

	/**
	 * @return Auth_User_Abstract[]
	 */
	abstract public function getUsers();


	/**
	 * @return Auth_Role_Privilege_Abstract[]
	 */
	abstract public function getPrivileges();

	/**
	 * Returns privilege values or empty array if the role does not have the privilege
	 *
	 * @param string $privilege
	 * @return array
	 */
	abstract public function getPrivilegeValues( $privilege );

	/**
	 * Data format:
	 *
	 * <code>
	 * array(
	 *      'privilege' => array('value1', 'value2')
	 * )
	 * </code>
	 *
	 * @param array $privileges
	 */
	abstract public function setPrivileges(array $privileges);

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
	abstract public function setPrivilege( $privilege, array $values );

	/**
	 * Example:
	 *
	 * privilege: save_object
	 *
	 * @param string $privilege
	 */
	abstract public function removePrivilege( $privilege );

	/**
	 * Example:
	 *
	 * privilege: save_object
	 * values: object_ID_1
	 *
	 * @param string $privilege
	 * @param mixed $value
	 * @return bool
	 */
	abstract public function getHasPrivilege( $privilege, $value );

	/**
	 * @return Auth_Role_Abstract[]
	 */
	abstract public function getRolesList();

	/**
	 * @return DataModel_Fetch_Data_Assoc
	 */
	abstract public function getRolesListAsData();

}