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
 * Class Auth_Role_Privilege_Abstract
 *
 * @JetFactory:class = 'Jet\\Auth_Factory'
 * @JetFactory:method = 'getPrivilegeInstance'
 * @JetFactory:mandatory_parent_class = 'Jet\\Auth_Role_Privilege_Abstract'
 *
 * @JetDataModel:name = 'role_privilege'
 * @JetDataModel:parent_model_class_name = 'Jet\\Auth_Role_Abstract'
 */
abstract class Auth_Role_Privilege_Abstract extends DataModel_Related_1toN {


	/**
	 * @param $privilege
	 * @param mixed[] $values
	 */
	public function __construct( $privilege='', array $values=array() ) {

		if($privilege) {
			$this->setPrivilege($privilege);
			$this->setValues($values);
		}

		parent::__construct();
	}

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