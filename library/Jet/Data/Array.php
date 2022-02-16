<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use JsonSerializable;

/**
 *
 */
class Data_Array extends BaseObject implements BaseObject_Interface_Serializable_JSON
{

	const PATH_DELIMITER = '/';


	/**
	 *
	 * @var array
	 */
	protected array $data = [];


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
	public function getRawData(): array
	{
		return $this->data;
	}


	/**
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function exists( string $key ): bool
	{
		if( !$key ) {
			return false;
		}

		$target = &$this->data;

		if( $key[0] === static::PATH_DELIMITER ) {
			$path = explode( static::PATH_DELIMITER, trim( $key, static::PATH_DELIMITER ) );

			if( !$path ) {
				return false;
			}

			$key = array_pop( $path );

			foreach( $path as $part ) {

				if(
					is_array( $target ) &&
					isset( $target[$part] )
				) {
					$target = &$target[$part];
				} else {

					return false;

				}

			}
		}

		if(
			is_array( $target ) &&
			isset( $target[$key] )
		) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function set( string $key, mixed $value ): void
	{

		if( !$key ) {
			return;
		}

		$target = &$this->data;

		if( $key[0] === static::PATH_DELIMITER ) {
			$path = explode( static::PATH_DELIMITER, trim( $key, static::PATH_DELIMITER ) );


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
	 *
	 * @param string $key
	 */
	public function remove( string $key ): void
	{
		if( !$key ) {
			return;
		}

		$target = &$this->data;

		if( $key[0] === static::PATH_DELIMITER ) {
			$path = explode( static::PATH_DELIMITER, trim( $key, static::PATH_DELIMITER ) );

			if( !$path ) {
				return;
			}

			$key = array_pop( $path );

			foreach( $path as $part ) {

				if(
					is_array( $target ) &&
					isset( $target[$part] )
				) {
					$target = &$target[$part];
				} else {

					return;

				}

			}
		}


		if(
			is_array( $target ) &&
			isset( $target[$key] )
		) {
			unset( $target[$key] );
		}
	}

	/**
	 *
	 * @param string $key
	 * @param int $default_value
	 *
	 * @return int
	 */
	public function getInt( string $key, int $default_value = 0 ): int
	{
		return (int)$this->getRaw( $key, $default_value );
	}

	/**
	 *
	 * @param string $key
	 * @param mixed $default_value
	 *
	 * @return mixed
	 */
	public function getRaw( string $key, mixed $default_value = null ): mixed
	{
		if( !$key ) {
			return $default_value;
		}

		$target = &$this->data;

		if( $key[0] === static::PATH_DELIMITER ) {
			$path = explode( static::PATH_DELIMITER, trim( $key, static::PATH_DELIMITER ) );

			if( !$path ) {
				return false;
			}

			$key = array_pop( $path );

			foreach( $path as $part ) {

				if(
					is_array( $target ) &&
					isset( $target[$part] )
				) {
					$target = &$target[$part];
				} else {

					return $default_value;

				}

			}
		}

		if(
			is_array( $target ) &&
			isset( $target[$key] )
		) {
			return $target[$key];
		} else {
			return $default_value;
		}
	}

	/**
	 *
	 * @param string $key
	 * @param float $default_value (optional, default = 0.0)
	 *
	 * @return float
	 */
	public function getFloat( string $key, float $default_value = 0.0 ): float
	{
		return (float)$this->getRaw( $key, $default_value );
	}

	/**
	 *
	 * @param string $key
	 * @param bool $default_value (optional, default = false)
	 *
	 * @return bool
	 */
	public function getBool( string $key, bool $default_value = false ): bool
	{
		return (bool)$this->getRaw( $key, $default_value );
	}

	/**
	 *
	 * @param string $key
	 * @param string $default_value (optional, default = '')
	 * @param array $valid_values (optional)
	 *
	 * @return string
	 */
	public function getString( string $key, string $default_value = '', array $valid_values = [] ): string
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
	 * @return string
	 */
	public function export(): string
	{
		$result = static::_export( $this->data );

		$result .= ';' . PHP_EOL;

		return $result;
	}

	/**
	 * @param array $data
	 * @param int $level
	 * @param string $path
	 *
	 * @return string
	 */
	public static function _export( array $data, int $level = 0, string $path = '' ): string
	{
		$result = '';
		$next_level = $level + 1;

		$indent = str_pad( '', $level, "\t" );



		$result .= '[' . PHP_EOL;

		$my_root_path = $path . static::PATH_DELIMITER;

		foreach( $data as $key => $value ) {

			$my_path = $my_root_path . $key;


			if( is_int( $key ) ) {
				$result .= $indent . "\t";

			} else {
				$result .= $indent . "\t" . '\'' . str_replace( "'", "\\'", $key ) . '\' => ';
			}

			if( is_array( $value ) ) {
				$result .= static::_export( $value, $next_level, $my_path  ) . '';
			} else if( is_object( $value ) ) {
				$class_name = get_class( $value );

				if( is_subclass_of( $value, JsonSerializable::class ) ) {
					$object_values = $value->jsonSerialize();
				} else {
					$object_values = get_object_vars( $value );
				}


				$result .= $class_name . '::__set_state( ' . static::_export(
						$object_values, $next_level, $my_path
					) . ' )';
			} else {
				$result .= var_export( $value, true );
			}

			$result .= ',' . PHP_EOL;

		}
		$result .= $indent . ']';

		return $result;

	}

	/**
	 * @return string
	 */
	public function toJSON(): string
	{
		$data = $this->jsonSerialize();

		return json_encode( $data );
	}

	/**
	 *
	 */
	public function jsonSerialize(): array
	{
		$data = $this->data;

		return $this->_jsonSerializeTraverse( $data );
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	protected function _jsonSerializeTraverse( array $data ): array
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
				$data[$key] = $val->jsonSerialize();
			}
		}

		return $data;
	}


}