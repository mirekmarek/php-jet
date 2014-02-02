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
 * Class Auth_Role_Privilege_Default
 *
 * @JetDataModel:database_table_name = 'Jet_Auth_Roles_Privileges'
 * @JetDataModel:parent_model_class_name = 'Jet\\Auth_Role_Default'
 */
class Auth_Role_Privilege_Default extends Auth_Role_Privilege_Abstract {

	/**
	 * @var string
	 */
	protected $Jet_Auth_Role_ID = '';

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
	 *
	 * @var string
	 */
	protected $privilege = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_ARRAY
	 * @JetDataModel:item_type = 'String'
	 *
	 * @var array
	 */
	protected $values = array ();


	/**
	 * @param $privilege
	 * @param mixed[] $values
	 */
	public function initNew( $privilege, array $values ) {
		$this->generateID();
		$this->privilege = $privilege;
		$this->values = $values;
	}

	/**
	 * @return string
	 */
	public function getPrivilege() {
		return $this->privilege;
	}

	/**
	 * @param string $privilege
	 */
	public function setPrivilege($privilege) {
		$this->privilege = $privilege;
	}

	/**
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function getHasValue( $value ) {
		return in_array($value, $this->values);
	}

	/**
	 * @return mixed[]
	 */
	public function getValues() {
		return $this->values;
	}

	/**
	 * @param array $values
	 */
	public function setValues(array $values) {
		$this->values = $values;
	}

	/**
	 * DataModel method
	 *
	 * @return string
	 */
	public function getArrayKeyValue() {
		return $this->privilege;
	}

	/**
	 * DataModel method
	 *
	 * @return mixed[]
	 */
	protected function _jsonSerializeItem() {
		return $this->values;
	}

}