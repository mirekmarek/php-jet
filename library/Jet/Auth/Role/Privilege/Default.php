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

class Auth_Role_Privilege_Default extends Auth_Role_Privilege_Abstract {
	/**
	 * @var string
	 */
	protected static $__data_model_parent_model_class_name = "Jet\\Auth_Role_Default";

	/**
	 * @var array
	 */
	protected static $__data_model_properties_definition = array(
		"privilege" => array(
			"type" => self::TYPE_STRING,
			"max_len" => 100,
			"is_required" => true
		),
		"values" => array(
			"type" => self::TYPE_ARRAY,
			"item_type" => self::TYPE_STRING
		)
	);

	/**
	 * @var string
	 */
	protected $Jet_Auth_Role_ID = "";

	/**
	 * @var string
	 */
	protected $ID = "";
	/**
	 * @var string
	 */
	protected $privilege = "";

	/**
	 * @var mixed[]
	 */
	protected $values = array();


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