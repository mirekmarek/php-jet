<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

/**
 *
 */
abstract class Mvc_View_Abstract extends BaseObject
{
	/**
	 * @var string
	 */
	protected static string $script_file_suffix = 'phtml';

	/**
	 *
	 * view path information (<!-- VIEW START: /view/dir/view.phtml -->, <!-- VIEW START: /view/dir/view.phtml -->)
	 *
	 * @var bool
	 */
	protected static bool $add_script_path_info = false;

	/**
	 * View dir
	 *
	 * @var string
	 */
	protected string $_scripts_dir = '';

	/**
	 * Full view file path (/some/dir/view-file.phtml)
	 * Set by Mvc_View::render()
	 *
	 * @var string
	 */
	protected string $_script_name = '';

	/**
	 * @var string
	 */
	protected string $_script_path = '';

	/**
	 * View or layout script variables.
	 *
	 * Example:
	 *   Controller script code example:
	 *    $layout->setVar('test_variable', 'Hello world!');
	 *
	 *   Layout script example:
	 *    <p><?=$this->getString('test_variable'); ?></p>
	 *
	 *   Output:
	 *     <p>Hello world!</p>
	 *
	 *
	 * @var Data_Array|null
	 */
	protected Data_Array|null $_data = null;

	/**
	 * @var callable[]
	 */
	protected array $_postprocessors = [];

	/**
	 * @return string
	 */
	public static function getScriptFileSuffix(): string
	{
		return static::$script_file_suffix;
	}

	/**
	 * @param string $script_file_suffix
	 */
	public static function setScriptFileSuffix( string $script_file_suffix ): void
	{
		static::$script_file_suffix = $script_file_suffix;
	}


	/**
	 *
	 * @return bool
	 */
	public static function getAddScriptPathInfoEnabled(): bool
	{
		return static::$add_script_path_info;
	}

	/**
	 *
	 * @param bool $enabled
	 *
	 */
	public static function setAddScriptPathInfoEnabled( $enabled = true ): void
	{
		static::$add_script_path_info = (bool)$enabled;
	}

	/**
	 * @return string
	 */
	public function getScriptsDir(): string
	{
		return $this->_scripts_dir;
	}

	/**
	 * @param string $scripts_dir
	 */
	public function setScriptsDir( string $scripts_dir ): void
	{
		if( $scripts_dir[strlen( $scripts_dir ) - 1] != '/' ) {
			$scripts_dir .= '/';
		}

		$this->_scripts_dir = $scripts_dir;
	}

	/**
	 *
	 * @return string
	 */
	public function getScriptName(): string
	{
		return $this->_script_name;
	}

	/**
	 *
	 * @param string $script_name
	 *
	 * @throws Mvc_View_Exception
	 */
	public function setScriptName( string $script_name ): void
	{
		if( $script_name === false ) {
			$this->_script_name = false;

			return;
		}

		if( str_contains( $script_name, '.' ) ) {
			throw new Mvc_View_Exception( 'Illegal script file name', Mvc_View_Exception::CODE_INVALID_VIEW_NAME );
		}


		$this->_script_name = $script_name;
	}

	/**
	 *
	 * @return string
	 */
	public function getScriptPath(): string
	{
		$file = $this->_scripts_dir . $this->_script_name . '.' . static::getScriptFileSuffix();

		$this->_script_path = $file;

		return $this->_script_path;
	}


	/**
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function varExists( string $key ): bool
	{
		return $this->_data->exists( $key );
	}

	/**
	 *
	 * @param string $key
	 * @param mixed $val
	 */
	public function setVar( string $key, mixed $val ): void
	{
		$this->_data->set( $key, $val );
	}

	/**
	 *
	 * @param string $key
	 */
	public function unsetVar( string $key ): void
	{
		$this->_data->remove( $key );
	}


	/**
	 *
	 * @param string $key
	 * @param mixed $default_value (optional; default: null)
	 *
	 * @return mixed
	 */
	public function getRaw( string $key, mixed $default_value = null ): mixed
	{
		return $this->_data->getRaw( $key, $default_value );
	}

	/**
	 *
	 * @param string $key
	 * @param int $default_value (optional - default: 0)
	 *
	 * @return int
	 */
	public function getInt( string $key, int $default_value = 0 ): int
	{
		return $this->_data->getInt( $key, $default_value );
	}

	/**
	 *
	 * @param string $key
	 * @param float $default_value (optional - default: 0)
	 *
	 * @return float
	 */
	public function getFloat( string $key, float $default_value = 0.0 ): float
	{
		return $this->_data->getFloat( $key, $default_value );
	}

	/**
	 *
	 * @param string $key
	 * @param bool $default_value (optional - default: false)
	 *
	 * @return bool
	 */
	public function getBool( string $key, bool $default_value = false ): bool
	{
		return $this->_data->getBool( $key, $default_value );
	}

	/**
	 *
	 * @param string $key
	 * @param string $default_value (optional - default: '')
	 *
	 * @return string
	 */
	public function getString( string $key, string $default_value = '' ): string
	{
		return $this->_data->getString( $key, $default_value );
	}


	/**
	 * @param callable $postprocessor
	 */
	public function addPostProcessor( callable $postprocessor )
	{
		$this->_postprocessors[] = $postprocessor;
	}

	/**
	 * @param string &$result
	 */
	protected function handlePostprocessors( string &$result )
	{
		foreach( $this->_postprocessors as $pp ) {
			$pp( $result, $this );
		}
	}

}