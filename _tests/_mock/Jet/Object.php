<?php
namespace JetTest;

use Jet;

class Object implements Jet\Object_Interface {

	use Jet\Object_Trait;
	use Jet\Object_Trait_MagicSleep;
	use Jet\Object_Trait_MagicSet;

	protected static $__factory_class_name = null;
	protected static $__factory_class_method_name = null;
	protected static $__factory_must_be_instance_of_class_name = null;

	public function __get( $property_name ) {
		if(isset($this->$property_name)) {
			//workaround for tests
			return $this->$property_name;
		}

		throw new \Exception("Undefined class property ".get_class($this)."::{$property_name}");
	}

	/**
	 *
	 * @param array $data
	 *
	 */
	public function __test_set_state(array $data) {

		foreach($data as $key=>$val) {
			$this->{$key} = $val;
		}
	}

	/**
	 *
	 * @param array $data
	 *
	 * @return Object
	 */
	public static function __test_create_instance(array $data) {
		$called_class = get_called_class();
		$_this = new $called_class();

		foreach($data as $key=>$val) {
			$_this->{$key} = $val;
		}
		return $_this;
	}

}
class_alias("JetTest\Object", "Jet\Object");
