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

abstract class Auth_Role_Privilege_Abstract extends DataModel_Related_1toN {
	/**
	 * @var string
	 */
	protected static $__factory_class_name = "Jet\\Auth_Factory";
	/**
	 * @var string
	 */
	protected static $__factory_class_method = "getPrivilegeInstance";

	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\Auth_Role_Privilege_Abstract";

	/**
	 * @var string
	 */
	protected static $__data_model_model_name = "Jet_Auth_Role_Privilege";

	/**
	 * @param $privilege
	 * @param mixed[] $values
	 */
	abstract function initNew( $privilege, array $values );

	/**
	 * @return string
	 */
	abstract public function getPrivilege();

	/**
	 * @param string $privilege
	 */
	abstract public function setPrivilege($privilege);

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	abstract public function getHasValue( $value );

	/**
	 * @return mixed[]
	 */
	abstract public function getValues();

	/**
	 * @param array $values
	 */
	abstract public function setValues(array $values);

}