<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class Mvc_View_Abstract
 * @package Jet
 */
abstract class Mvc_View_Abstract extends BaseObject
{
	/**
	 * @var string
	 */
	protected static $script_file_suffix = 'phtml';

	/**
	 *
	 * If the constant JET_DEBUG_MODE is true
	 * view path information (<!-- VIEW START: /view/dir/view.phtml -->, <!-- VIEW START: /view/dir/view.phtml -->)
	 *
	 * @var bool
	 */
	protected static $add_script_path_info = false;

	/**
	 * View dir
	 *
	 * @var string
	 */
	protected $_scripts_dir = '';
	/**
	 * Full view file path (/some/dir/view-file.phtml)
	 * Set by Mvc_View::render()
	 *
	 * @var string
	 */
	protected $_script_name = '';
	/**
	 * @var string
	 */
	protected $_script_path = '';
	/**
	 * View or layout script variables.
	 *
	 * Example:
	 *   Controller script code example:
	 *    $layout->setVar('test_variable', 'Hello world!');
	 *
	 *   Layout script example:
	 *    <p><?=$this->test_variable; ?></p>
	 *
	 *   Output:
	 *     <p>Hello world!</p>
	 *
	 *
	 * @var Data_Array
	 */
	protected $_data;

	/**
	 * @var callable[]
	 */
	protected $_postprocessors = [];

	/**
	 * @return string
	 */
	public static function getScriptFileSuffix()
	{
		return static::$script_file_suffix;
	}

	/**
	 * @param string $script_file_suffix
	 */
	public static function setScriptFileSuffix( $script_file_suffix )
	{
		static::$script_file_suffix = $script_file_suffix;
	}



	/**
	 *
	 * @return bool
	 */
	public static function getAddScriptPathInfoEnabled()
	{
		return static::$add_script_path_info;
	}

	/**
	 *
	 * @param bool $enabled
	 *
	 */
	public static function setAddScriptPathInfoEnabled( $enabled = true )
	{
		static::$add_script_path_info = (bool)$enabled;
	}

	/**
	 * @return string
	 */
	public function getScriptsDir()
	{
		return $this->_scripts_dir;
	}

	/**
	 * @param string $scripts_dir
	 */
	public function setScriptsDir( $scripts_dir )
	{
		if( $scripts_dir[strlen( $scripts_dir )-1]!='/' ) {
			$scripts_dir .= '/';
		}

		$this->_scripts_dir = $scripts_dir;
	}

	/**
	 *
	 * @return string
	 */
	public function getScriptName()
	{
		return $this->_script_name;
	}

	/**
	 *
	 * @param string $script_name
	 *
	 * @throws Mvc_View_Exception
	 *
	 */
	public function setScriptName( $script_name )
	{
		if( $script_name===false ) {
			$this->_script_name = false;

			return;
		}

		if( strpos( '.', $script_name )!==false ) {
			throw new Mvc_View_Exception(
				'Illegal script file name', Mvc_View_Exception::CODE_INVALID_VIEW_NAME
			);
		}


		$this->_script_name = $script_name;
	}

	/**
	 * @throws Mvc_View_Exception
	 *
	 * @return string
	 */
	public function getScriptPath()
	{
		$file = $this->_scripts_dir.$this->_script_name.'.'.static::getScriptFileSuffix();


		if( !IO_File::exists( $file ) ) {
			throw new Mvc_View_Exception(
				'File \''.$file.'\' does not exist',
				Mvc_View_Exception::CODE_FILE_DOES_NOT_EXIST
			);
		}

		if( !IO_File::isReadable( $file ) ) {
			throw new Mvc_View_Exception(
				'File \''.$file.'\' is not readable',
				Mvc_View_Exception::CODE_FILE_IS_NOT_READABLE
			);
		}

		$this->_script_path = $file;

		return $this->_script_path;
	}

	/**
	 * Allows testing with empty() and isset()
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function __isset( $key )
	{
		return $this->_data->exists( $key );
	}

	/**
	 * Allows testing with empty() and isset()
	 * Alias of existsVar()
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function varExists( $key )
	{
		return $this->_data->exists( $key );
	}

	/**
	 * Sets view variable
	 *
	 * @param string $key
	 * @param mixed  $val
	 *
	 * @throws Mvc_View_Exception
	 */
	public function setVar( $key, $val )
	{
		$this->_data->set( $key, $val );
	}

	/**
	 * UnSets view variable
	 *
	 * @param string $key
	 */
	public function unsetVar( $key )
	{
		$this->_data->remove( $key );
	}

	/**
	 * Clears all assigned variables
	 *
	 */
	public function clearVars()
	{
		$this->_data->clearData();
	}

	/**
	 * Get raw value from data/path
	 *
	 * @param string $key
	 * @param mixed  $default_value (optional; default: null)
	 *
	 * @return mixed
	 */
	public function getRaw( $key, $default_value = null )
	{
		return $this->_data->getRaw( $key, $default_value );
	}

	/**
	 * Get data value as int or (int)$default_value if not exists
	 *
	 * @param string $key
	 * @param int    $default_value (optional - default: 0)
	 *
	 * @return int
	 */
	public function getInt( $key, $default_value = 0 )
	{
		return $this->_data->getInt( $key, $default_value );
	}

	/**
	 * Get data value as float or (float)$default_value if not exists
	 *
	 * @param string $key
	 * @param float  $default_value (optional - default: 0)
	 *
	 * @return float
	 */
	public function getFloat( $key, $default_value = 0.0 )
	{
		return $this->_data->getFloat( $key, $default_value );
	}

	/**
	 * Get data value as bool or (bool)$default_value if not exists
	 *
	 * @param string $key
	 * @param bool   $default_value (optional - default: false)
	 *
	 * @return bool
	 */
	public function getBool( $key, $default_value = false )
	{
		return $this->_data->getBool( $key, $default_value );
	}

	/**
	 * Get data value as string or (string)$default_value if not exists
	 *
	 * @param string $key
	 * @param string $default_value (optional - default: '')
	 *
	 * @return string
	 */
	public function getString( $key, $default_value = '' )
	{
		return $this->_data->getString( $key, $default_value );
	}

	/**
	 * Get view data
	 *
	 * @return Data_Array
	 */
	public function getData()
	{
		return $this->_data;
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
	protected function handlePostprocessors( &$result )
	{
		foreach( $this->_postprocessors as $pp ) {
			$pp( $result );
		}
	}

}