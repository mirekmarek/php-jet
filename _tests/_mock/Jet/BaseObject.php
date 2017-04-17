<?php
namespace JetTest;

use Jet\BaseObject_Interface;
use Jet\BaseObject_Trait;
use Jet\BaseObject_Trait_MagicSleep;
use Jet\BaseObject_Trait_MagicSet;
use Jet\BaseObject_Trait_MagicClone;

class BaseObject implements BaseObject_Interface {

	use BaseObject_Trait;
	use BaseObject_Trait_MagicSleep;
	use BaseObject_Trait_MagicSet;
	use BaseObject_Trait_MagicClone;

	/**
	 * @param string $property_name
	 * @return mixed
	 * @throws \Exception
	 */
	public function __get( $property_name ) {
		if(isset($this->{$property_name})) {
			//workaround for tests
			return $this->{$property_name};
		}

		throw new \Exception('Undefined class property '.get_class($this).'::'.$property_name );
	}


	/**
	 * @param string $property_name
	 * @param mixed $value
	 */
	public function __test_set( $property_name, $value ) {
		$this->{$property_name} = $value;
	}

	/**
	 * @param string $property_name
	 * @param mixed $value
	 */
	public static function __test_set_static( $property_name, $value ) {
		static::$$property_name = $value;
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
	 * @return BaseObject
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
class_alias('JetTest\BaseObject', 'Jet\BaseObject');


