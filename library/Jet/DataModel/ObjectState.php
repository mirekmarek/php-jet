<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2015 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Query
 */
namespace Jet;

class DataModel_ObjectState extends Object {

	/**
	 * @var array
	 */
	protected static $data = [];

	/**
	 * @param Object $instance
	 * @param string $key
	 * @param mixed $default_value (optional)
	 *
	 * @return &mixed
	 */
	public static function &getVar( Object $instance, $key, $default_value=null ) {

		$object_key = $instance->getObjectIdentificationKey();

		if(!array_key_exists($object_key, static::$data)) {
			static::$data[$object_key] = [];
		}

		if(!array_key_exists($key, static::$data[$object_key])) {
			static::$data[$object_key][$key] = $default_value;
		}

		return static::$data[$object_key][$key];
	}

	/**
	 * @param $instance
	 */
	public static function destruct( Object $instance ) {

		$object_key = $instance->getObjectIdentificationKey();

		if(array_key_exists($object_key, static::$data)) {
			unset(static::$data[$object_key]);
		}
	}

}