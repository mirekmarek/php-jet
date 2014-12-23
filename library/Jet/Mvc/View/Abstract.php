<?php
/**
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
 * @package Mvc
 * @subpackage Mvc_Layout
 */
namespace Jet;

abstract class Mvc_View_Abstract extends Object {
	const TAG_MODULE = 'jet_module';
	const TAG_PART = 'jet_view_part';
	const SCRIPT_FILE_SUFFIX = 'phtml';

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
	 *
	 * If the constant JET_DEBUG_MODE is true
	 * view path information (<!-- VIEW START: /view/dir/view.phtml -->, <!-- VIEW START: /view/dir/view.phtml -->)
	 *
	 * @var bool
	 */
	protected static $_add_script_path_info = JET_DEBUG_MODE;

	/**
	 * View or layout script variables.
	 *
	 * Example:
	 *   Controller script code example:
	 *	$layout->setVar('test_variable', 'Hello world!');
	 *
	 *   Layout script example:
	 *	<p><?=$this->test_variable; ?></p>
	 *
	 *   Output:
	 *     <p>Hello world!</p>
	 *
	 *
	 * NOTICE:
	 *
	 * Variable can be an Object. If variable is an object and instance of Mvc_Layout_Postprocessor_Interface or Mvc_View_Postprocessor_Interface then the output is processed by the object
	 * @see Mvc_Layout_Postprocessor_Interface
	 * @see Mvc_View_Postprocessor_Interface
	 *
	 *
	 * @var Data_Array
	 */
	protected $_data;


	/**
	 * @param string $scripts_dir
	 */
	public function setScriptsDir($scripts_dir) {
		if( $scripts_dir[strlen($scripts_dir)-1]!='/' )  {
			$scripts_dir .= '/';
		}

		$this->_scripts_dir = $scripts_dir;
	}

	/**
	 * @return string
	 */
	public function getScriptsDir() {
		return $this->_scripts_dir;
	}


	/**
	 *
	 * @param string $script_name
	 *
	 * @throws Mvc_View_Exception
	 *
	 */
	public function setScriptName( $script_name ) {
		if( $script_name===false ) {
			$this->_script_name = false;
			return;
		}

		if( strpos('.', $script_name)!==false ) {
			throw new Mvc_View_Exception(
				'Illegal script file name',
				Mvc_View_Exception::CODE_INVALID_VIEW_NAME
			);
		}

		$script_name = strtolower($script_name);

		$this->_script_name = $script_name;
	}


	/**
	 *
	 * @return string
	 */
	public function getScriptName() {
		return $this->_script_name;
	}

	/**
	 * @throws Mvc_View_Exception
	 *
	 * @return string
	 */
	public function getScriptPath() {
		$file = $this->_scripts_dir . $this->_script_name . '.'.static::SCRIPT_FILE_SUFFIX;

		if( !IO_File::exists($file) ) {
			throw new Mvc_View_Exception(
				'File \''.$file.'\' does not exist',
				Mvc_View_Exception::CODE_FILE_DOES_NOT_EXIST
			);
		}

		if( !IO_File::isReadable($file) ) {
			throw new Mvc_View_Exception(
				'File \''.$file.'\' is not readable',
				Mvc_View_Exception::CODE_FILE_IS_NOT_READABLE
			);
		}

		$this->_script_path = $file;

	}



	/**
	 * Returns a view variable (or NULL if variable is not set)
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function __get($key) {
		$var = $this->_data->getRaw($key);
		if(
			is_object($var) ||
			is_array($var)
		) {
			return $var;
		}
		return Data_Text::htmlSpecialChars($var);
	}

	/**
	 * Allows testing with empty() and isset()
	 *
	 * @param string $key
	 * @return bool
	 */
	public function __isset($key) {
		return $this->_data->exists($key);
	}


	/**
	 * Allows testing with empty() and isset()
	 * Alias of existsVar()
	 *
	 * @param string $key
	 * @return bool
	 */
	public function varExists($key) {
		return $this->_data->exists($key);
	}


	/**
	 * Sets view variable
	 *
	 * @param string $key
	 * @param mixed $val
	 *
	 * @throws Mvc_View_Exception
	 */
	public function setVar($key, $val) {
		$this->_data->set($key, $val);
	}

	/**
	 * UnSets view variable
	 *
	 * @param string $key
	 */
	public function unsetVar($key) {
		$this->_data->remove($key);
	}

	/**
	 * Clears all assigned variables
	 *
	 */
	public function clearVars() {
		$this->_data->clearData();
	}


	/**
	 * Get raw value from data/path
	 *
	 * @param string $key
	 * @param mixed $default_value (optional; default: null)
	 *
	 * @return mixed
	 */
	public function getRaw($key, $default_value = null){
		return $this->_data->getRaw($key, $default_value);
	}

	/**
	 * Get data value as int or (int)$default_value if not exists
	 *
	 * @param string $key
	 * @param int $default_value(optional - default: 0)
	 *
	 * @return int
	 */
	public function getInt($key, $default_value = 0){
		return $this->_data->getInt($key, $default_value);
	}

	/**
	 * Get data value as float or (float)$default_value if not exists
	 *
	 * @param string $key
	 * @param float $default_value(optional - default: 0)
	 *
	 * @return float
	 */
	public function getFloat($key, $default_value = 0.0){
		return $this->_data->getFloat($key, $default_value);
	}

	/**
	 * Get data value as bool or (bool)$default_value if not exists
	 *
	 * @param string $key
	 * @param bool $default_value(optional - default: false)
	 *
	 * @return bool
	 */
	public function getBool($key, $default_value = false){
		return $this->_data->getBool($key, $default_value);
	}

	/**
	 * Get data value as string or (string)$default_value if not exists
	 *
	 * @param string $key
	 * @param string $default_value(optional - default: '')
	 *
	 * @return string
	 */
	public function getString($key, $default_value = ''){
		return $this->_data->getString($key, $default_value);
	}

	/**
	 * Get view data
	 *
	 * @return Data_Array
	 */
	public function getData(){
		return $this->_data;
	}


	/**
	 * Handle the parts tag ( <jet_view_part name='part-name'/> or <jet_layout_part name='part-name'/> )
	 *
	 * Sometimes it is appropriate or necessary to make the view consisted of several parts.
	 * Each of the view part must be placed in the directory 'parts':
	 *
	 * view-dir/parts/view-part.phtml
	 *
	 * View part file name format: [a-z0-9\-]*.phtml
	 *
	 * @param string &$result
	 * @throws Mvc_View_Exception
	 */
	protected function handleParts( &$result ) {
		$matches = array();
		if(preg_match_all('/<'.static::TAG_PART.'[ ]{1,}name="([a-z0-9\-]*)"[^\/]*\/>/i', $result, $matches, PREG_SET_ORDER)) {

			foreach( $matches as $match ) {
				$orig = $match[0];
				$part = $match[1];

				$file = $this->_scripts_dir . 'parts/'.$part.'.'.static::SCRIPT_FILE_SUFFIX;

				if( !IO_File::exists($file) ) {
					throw new Mvc_View_Exception(
						'File \''.$file.'\' does not exist',
						Mvc_View_Exception::CODE_FILE_DOES_NOT_EXIST
					);
				}

				if( !IO_File::isReadable($file) ) {
					throw new Mvc_View_Exception(
						'File \''.$file.'\' is not readable',
						Mvc_View_Exception::CODE_FILE_IS_NOT_READABLE
					);
				}

				unset($file);

				ob_start();
				/** @noinspection PhpIncludeInspection */
				include $this->_scripts_dir . 'parts/'.$part.'.phtml';
				$part = ob_get_clean();

				$result = str_replace($orig, $part, $result);
			}
		}

	}


	/**
	 * Gets if adding path info into the output is enabled
	 *
	 * @return bool
	 */
	public static function getAddScriptPathInfoEnabled(){
		return static::$_add_script_path_info;
	}

	/**
	 * Sets if adding path info into the output is enabled
	 *
	 * @param bool $enabled
	 *
	 */
	public static function setAddScriptPathInfoEnabled($enabled=true){
		static::$_add_script_path_info = (bool)$enabled;
	}

	/**
	 * Handle the Module tag  ( <jet_module> )
	 *
	 * In fact it search and dispatch all modules included by the tag
	 *
	 * @see Mvc/readme.txt
	 *
	 * @param string &$result
	 */
	protected function handleModules(&$result) {

		$matches = array();

		if( preg_match_all('/<'.self::TAG_MODULE.'([^>]*)\>/i', $result, $matches, PREG_SET_ORDER) ) {

			foreach($matches as $match) {
				$orig_str = $match[0];

				$_properties = substr(trim($match[1]), 0, -1);
				$_properties = preg_replace('/[ ]{2,}/i', ' ', $_properties);
				$_properties = explode( '" ', $_properties );


				$properties = array();


				foreach( $_properties as $property ) {
					if( !$property || strpos($property, '=')===false ) {
						continue;
					}

					$property = explode('=', $property);

					$property_name = array_shift($property);
					$property_value = implode('=', $property);

					$property_name = strtolower($property_name);
					$property_value = str_replace('"', '', $property_value);

					$properties[$property_name] = $property_value;

				}


				$module_name = $properties['data-module-name'];
                $action = isset($properties['data-action']) ? $properties['data-action'] : Mvc_Dispatcher::DEFAULT_ACTION;
				$action_params = isset($properties['data-action-params']) ? json_decode( htmlspecialchars_decode($properties['data-action-params']), true ) : [];

//				var_dump($module_name, $action, $action_params);

				$content_data = Mvc_Factory::getPageContentInstance();

				if($action_params) {
					$action_params = array($action_params);
				}

				$qi = new Mvc_Dispatcher_Queue_Item(
					$module_name,
					$action,
					$action_params,
					$content_data
				);

				$qi->setCustomServiceType( Mvc_Router::SERVICE_TYPE_STANDARD );

				$output = Mvc_Router::getCurrentRouterInstance()->getDispatcherInstance()->renderQueueItem($qi);

				//var_dump($output);

				$result = str_replace($orig_str, $output, $result);

			}
		}

	}

}