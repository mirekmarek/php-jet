<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class Data_Array extends BaseObject implements BaseObject_Serializable
{

	const PATH_DELIMITER = '/';


	/**
	 *
	 * @var array
	 */
	protected $data = [];


	/**
	 *
	 * @param array $data
	 */
	public function __construct( array $data = [] )
	{
		$this->data = $data;
	}


	/**
	 *
	 * @return array
	 */
	public function getRawData()
	{
		return $this->data;
	}

	/**
	 *
	 * @param array $data
	 */
	public function appendData( array $data )
	{
		$this->data = array_merge( $this->data, $data );
	}

	/**
	 *
	 * @param array $data
	 */
	public function setData( array $data )
	{
		$this->data = $data;
	}

	/**
	 *
	 */
	public function clearData()
	{
		$this->data = [];
	}

	/**
	 * Is data/path value set?
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function exists( $key )
	{
		if( !$key ) {
			return false;
		}

		$target = &$this->data;

		if( $key[0]===self::PATH_DELIMITER ) {
			$path = explode( self::PATH_DELIMITER, trim( $key, self::PATH_DELIMITER ) );

			if( !$path ) {
				return false;
			}

			$key = array_pop( $path );

			foreach( $path as $part ) {

				if( is_array( $target )&&isset( $target[$part] ) ) {
					$target = &$target[$part];
				} else {

					return false;

				}

			}
		}

		if( is_array( $target )&&isset( $target[$key] ) ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Set data value by given key/path
	 *
	 * @param string $key
	 * @param mixed  $value
	 *
	 */
	public function set( $key, $value )
	{

		if( !$key ) {
			return;
		}

		$key = (string)$key;

		$target = &$this->data;

		if( $key[0]===self::PATH_DELIMITER ) {
			$path = explode( self::PATH_DELIMITER, trim( $key, self::PATH_DELIMITER ) );


			if( !$path ) {
				return;
			}

			$key = array_pop( $path );

			foreach( $path as $part ) {
				if( !is_array( $target ) ) {
					return;
				}

				if( !isset( $target[$part] ) ) {
					$target[$part] = [];
				}

				$target = &$target[$part];

			}
		}

		if( is_array( $target ) ) {
			$target[$key] = $value;
		}
	}


	/**
	 * Unset value from data/path
	 *
	 * @param string $key
	 */
	public function remove( $key )
	{
		if( !$key ) {
			return;
		}

		$target = &$this->data;

		if( $key[0]===self::PATH_DELIMITER ) {
			$path = explode( self::PATH_DELIMITER, trim( $key, self::PATH_DELIMITER ) );

			if( !$path ) {
				return;
			}

			$key = array_pop( $path );

			foreach( $path as $part ) {

				if( is_array( $target )&&isset( $target[$part] ) ) {
					$target = &$target[$part];
				} else {

					return;

				}

			}
		}


		if( is_array( $target )&&isset( $target[$key] ) ) {
			unset( $target[$key] );
		}
	}

	/**
	 *
	 * @param string $key
	 * @param int    $default_value (optional, default = 0)
	 *
	 * @return int
	 */
	public function getInt( $key, $default_value = 0 )
	{
		return (int)$this->getRaw( $key, $default_value );
	}

	/**
	 *
	 * @param string $key
	 * @param mixed  $default_value (optional; default: null)
	 *
	 * @return mixed
	 */
	public function getRaw( $key, $default_value = null )
	{
		if( !$key ) {
			return $default_value;
		}

		$target = &$this->data;

		if( $key[0]===self::PATH_DELIMITER ) {
			$path = explode( self::PATH_DELIMITER, trim( $key, self::PATH_DELIMITER ) );

			if( !$path ) {
				return false;
			}

			$key = array_pop( $path );

			foreach( $path as $part ) {

				if( is_array( $target ) && isset( $target[$part] ) ) {
					$target = &$target[$part];
				} else {

					return $default_value;

				}

			}
		}

		if( is_array( $target )&&isset( $target[$key] ) ) {
			return $target[$key];
		} else {
			return $default_value;
		}
	}

	/**
	 *
	 * @param string $key
	 * @param float  $default_value (optional, default = 0.0)
	 *
	 * @return float
	 */
	public function getFloat( $key, $default_value = 0.0 )
	{
		return (float)$this->getRaw( $key, $default_value );
	}

	/**
	 *
	 * @param string $key
	 * @param bool   $default_value (optional, default = false)
	 *
	 * @return bool
	 */
	public function getBool( $key, $default_value = false )
	{
		return (bool)$this->getRaw( $key, $default_value );
	}

	/**
	 *
	 * @param string $key
	 * @param string $default_value (optional, default = '')
	 * @param array  $valid_values (optional)
	 *
	 * @return string
	 */
	public function getString( $key, $default_value = '', array $valid_values = [] )
	{

		$value = $this->getRaw( $key, $default_value );

		if( $valid_values ) {
			if( !in_array( $value, $valid_values ) ) {
				$value = $default_value;
			}
		}

		if( is_bool( $value ) ) {
			$value = $value ? 1 : 0;
		}

		return Data_Text::htmlSpecialChars( (string)$value );
	}

	/**
	 *
	 * @param array $comments
	 *
	 * @return string
	 */
	public function export( array $comments = [] )
	{
		$result = $this->_export( '', $this->data, 0, $comments );

		$result .= ';'.JET_EOL;

		return $result;
	}

	/**
	 * @param string $path
	 * @param array  $data
	 * @param int    $level
	 * @param array  $comments
	 *
	 * @return string
	 */
	protected function _export( $path, array $data, $level, array $comments )
	{
		$result = '';
		$next_level = $level+1;

		$indent = str_pad( '', $level, JET_TAB );


		$comment = '';
		if( isset( $comments[$path] ) ) {
			$comment .= JET_TAB.'/* '.$comments[$path].' */';
		}


		$result .= '['.$comment.JET_EOL;

		$my_root_path = $path.static::PATH_DELIMITER;

		foreach( $data as $key => $value ) {

			$my_path = $my_root_path.$key;

			$comment = '';
			if( isset( $comments[$my_path] ) ) {
				$comment .= JET_TAB.'/* '.$comments[$my_path].' */';
			}

			if( is_int( $key ) ) {
				$result .= $indent.JET_TAB;

			} else {
				$result .= $indent.JET_TAB.'\''.str_replace( "'", "\\'", $key ).'\' => ';
			}

			if( is_array( $value ) ) {
				$result .= $this->_export( $my_path, $value, $next_level, $comments ).'';
			} else if( is_object( $value ) ) {
				$class_name = get_class( $value );

				if( is_subclass_of( $value, '\JsonSerializable' ) ) {
					/**
					 * @var \JsonSerializable $value
					 */
					$object_values = $value->jsonSerialize();
				} else {
					$object_values = get_object_vars( $value );
				}


				$result .= $class_name.'::__set_state( '.$this->_export(
						$my_path, $object_values, $next_level, $comments
					).' )';
			} else {
				$result .= var_export( $value, true ).$comment;
			}

			$result .= ','.JET_EOL;

		}
		$result .= $indent.']';

		return $result;

	}

	/**
	 * @return string
	 */
	public function toJSON()
	{
		$data = $this->jsonSerialize();

		return json_encode( $data );
	}

	/**
	 *
	 */
	public function jsonSerialize()
	{
		$data = $this->data;

		return $this->_jsonSerializeTraverse( $data );
	}

	/**
	 * @param array $data
	 *
	 * @return mixed
	 */
	protected function _jsonSerializeTraverse( array $data )
	{
		foreach( $data as $key => $val ) {

			if( is_array( $val ) ) {
				$data[$key] = $this->_jsonSerializeTraverse( $val );
				continue;
			}

			if( !is_object( $val ) ) {
				continue;
			}

			if( is_subclass_of( $val, '\JsonSerializable' ) ) {
				/**
				 * @var \JsonSerializable $val
				 */
				$data[$key] = $val->jsonSerialize();
				continue;
			}
		}

		return $data;
	}

	/**
	 * @return string
	 */
	public function toXML()
	{
		$data = $this->jsonSerialize();

		return $this->_XMLSerialize( $data, 'data' );
	}

	/**
	 * @param mixed  $data
	 * @param string $tag
	 * @param string $prefix
	 *
	 * @return string
	 */
	protected function _XMLSerialize( $data, $tag, $prefix = '' )
	{
		$result = $prefix.'<'.$tag.'>'.JET_EOL;

		if( is_object( $data ) ) {
			$data = get_class_vars( $data );
		}

		foreach( $data as $key => $val ) {
			if( is_array( $val )||is_object( $val ) ) {
				if( is_int( $key ) ) {
					$key = 'item';
				}
				$result .= $this->_XMLSerialize( $val, $key, $prefix.JET_TAB );
			} else {
				if( is_bool( $val ) ) {
					$result .= $prefix.JET_TAB.'<'.$key.'>'.( $val ? 1 : 0 ).'</'.$key.'>'.JET_EOL;

				} else {
					$result .= $prefix.JET_TAB.'<'.$key.'>'.Data_Text::htmlSpecialChars( $val ).'</'.$key.'>'.JET_EOL;
				}
			}
		}
		$result .= $prefix.'</'.$tag.'>'.JET_EOL;

		return $result;
	}

}