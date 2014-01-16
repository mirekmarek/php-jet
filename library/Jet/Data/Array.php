<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Data
 * @subpackage Data_Array
 */
namespace Jet;

class Data_Array extends Object {

	const PATH_DELIMITER = '/';

		
	/**
	 *
	 * @var array 
	 */
	protected $data = array();


	/**
	 *
	 * @param array $data
	 */
	public function __construct(array $data = array()) {
		$this->data = $data;
	}


	/**
	 *
	 * @return array
	 */
	public function getRawData() {
		return $this->data;
	}

	/**
	 *
	 * @param array $data
	 */
	public function appendData( array $data ) {
		$this->data = array_merge( $this->data, $data );
	}

	/**
	 *
	 * @param array $data
	 */
	public function setData( array $data ) {
		$this->data = $data;
	}

	/**
	 *
	 */
	public function clearData() {
		$this->data = array();
	}

	/**
	 * Is data/path value set?
	 *
	 * @param string $key
	 * @return bool
	 */
	public function exists($key) {
		if(!$key) {
			return false;
		}

		$target = &$this->data;

		if( $key[0]===self::PATH_DELIMITER ){
			$path = explode(self::PATH_DELIMITER, trim($key, self::PATH_DELIMITER));

			if(!$path){
				return false;
			}

			$key = array_pop($path);

			foreach($path as $part){

				if(
					is_array($target) &&
					isset($target[$part])
				){
					$target = &$target[$part];
				} else {

					return false;

				}

			}
		}

		if(
			is_array($target) &&
			isset($target[$key])
		){
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Set data value by given key/path
	 *
	 * @param string $key
	 * @param mixed $value
	 *
	 */
	public function set( $key, $value ) {

		if(!$key) {
			return;
		}

		$target = &$this->data;

		if( $key[0]===self::PATH_DELIMITER ){
			$path = explode(self::PATH_DELIMITER, trim($key, self::PATH_DELIMITER));

			if(!$path){
				return;
			}

			$key = array_pop($path);

			foreach($path as $part){

				if(
					is_array($target) &&
					isset($target[$part])
				){
					$target = &$target[$part];
				} else {

					return;

				}

			}
		}

		if(
			is_array($target)
		){
			$target[$key] = $value;
		}
	}


	/**
	 * Unset value from data/path
	 *
	 * @param string $key
	 * @return bool
	 */
	public function remove( $key ) {
		if(!$key) {
			return;
		}

		$target = &$this->data;

		if( $key[0]===self::PATH_DELIMITER ){
			$path = explode(self::PATH_DELIMITER, trim($key, self::PATH_DELIMITER));

			if(!$path){
				return;
			}

			$key = array_pop($path);

			foreach($path as $part){

				if(
					is_array($target) &&
					isset($target[$part])
				){
					$target = &$target[$part];
				} else {

					return;

				}

			}
		}


		if(
			is_array($target) &&
			isset($target[$key])
		){
			unset($target[$key]);
		}
	}


	/**
	 *
	 * @param string $key
	 * @param mixed $default_value (optional; default: null)
	 *
	 * @return array()
	 */
	public function getRaw($key, $default_value = null ){
		if(!$key) {
			return $default_value;
		}

		$target = &$this->data;

		if( $key[0]===self::PATH_DELIMITER ){
			$path = explode(self::PATH_DELIMITER, trim($key, self::PATH_DELIMITER));

			if(!$path){
				return false;
			}

			$key = array_pop($path);

			foreach($path as $part){

				if(
					is_array($target) &&
					isset($target[$part])
				){
					$target = &$target[$part];
				} else {

					return $default_value;

				}

			}
		}

		if(
			is_array($target) &&
			isset($target[$key])
		){
			return $target[$key];
		} else {
			return $default_value;
		}
	}


	/**
	 *
	 * @param string $key
	 * @param int $default_value (optional, default = 0)
	 *
	 * @return int
	 */
	public function getInt($key, $default_value = 0){
		return (int)$this->getRaw($key, $default_value);
	}
	
	/**
	 *
	 * @param string $key
	 * @param float $default_value (optional, default = 0.0)
	 *
	 * @return float
	 */
	public function getFloat($key, $default_value = 0.0){
		return (float)$this->getRaw($key, $default_value);
	}
	
	/**
	 *
	 * @param string $key
	 * @param bool $default_value (optional, default = false)
	 * @return bool
	 */
	public function getBool($key, $default_value = false){
		return (bool)$this->getRaw($key, $default_value);
	}

	/**
	 *
	 * @param string $key
	 * @param string $default_value (optional, default = '')
	 *
	 * @return string
	 */
	public function getString($key, $default_value = ''){

		$value = $this->getRaw($key, $default_value);

		if(is_bool($value)){
			$value = $value?1:0;
		}

		return htmlspecialchars( (string)$value );
	}

	/**
	 *
	 * @param array $comments
	 *
	 * @return string
	 */
	public function export(array $comments = array()){
		$result = $this->_export( '', $this->data, 0, $comments );

		$result .= ';'.JET_EOL;

		return $result;
	}

	/**
	 * @param string $path
	 * @param array $data
	 * @param int $level
	 * @param array $comments
	 *
	 * @return string
	 */
	protected function _export( $path, array $data, $level, array $comments ) {
		$result = '';
		$next_level = $level + 1;

		$indent = str_pad('', $level, JET_TAB);


		$comment = '';
		if( isset($comments[$path]) ) {
			$comment .= JET_TAB.'/* '.$comments[$path].' */';
		}


		$result .= 'array('.$comment.JET_EOL;

		$my_root_path = $path . static::PATH_DELIMITER;

		foreach( $data as $key=>$value ) {

			$my_paht = $my_root_path.$key;

			$comment = '';
			if( isset($comments[$my_paht]) ) {
				$comment .= JET_TAB.'/* '.$comments[$my_paht].' */';
			}

			if(is_int($key)) {
				$result .= $indent.JET_TAB;

			} else {
				$result .= $indent.JET_TAB.'\''.$key.'\' => ';
			}

			if(is_array($value)) {
				$result .= $this->_export( $my_paht, $value, $next_level, $comments) . '';
			} else
			if(is_object($value)) {
				$class_name = get_class( $value );

				if( is_subclass_of( $value, '\JsonSerializable' ) ) {
					$object_values = $value->jsonSerialize();
				} else {
					$object_values = get_object_vars( $value );
				}


				$result .= $class_name.'::__set_state( '.$this->_export( $my_paht, $object_values, $next_level, $comments).' )';
			} else {
				$result .= var_export( $value, true ).$comment;
			}

			$result .= ','.JET_EOL;

		}
		$result .= $indent . ')';

		return $result;

	}

}