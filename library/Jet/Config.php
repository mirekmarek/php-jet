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
 * @abstract
 *
 * @category Jet
 * @package Config
 */
namespace Jet;

abstract class Config extends Object {
	/**
	 * Path to configuration data within main config file data
	 *
	 * @see Data_Array::getRaw() for paths usage explanation
	 *
	 * @var string
	 */
	protected static $__config_data_path = "";


	/**
	 * Is given section presence in config required (TRUE) or optional (FALSE)?
	 *
	 * @var bool
	 */
	protected static $__config_section_is_obligatory = true;


	/**
	 * Definition of config properties/options
	 *
	 * Looks like:
	 * array(
	 *  "property_name" => array(
	 *      "type" => one of Config::TYPE_*,
	 *      "description" => "Extended description of option",
	 *      "is_required" => true/false
	 *      "default_value" => "some default value"
	 *      "form_field_type" => Form::TYPE_* (optional, default: autodetect)
	 *      "form_field_label" => "Form filed label:"
	 *      "form_field_options" => array("option1" => "Option 1", "option2" => "Option 1", "option3"=>"Option 3", ...)
	 *      "form_field_error_messages" => array("error_code" => "Message", ...):
	 *      "form_field_get_default_value_callback" => callable
	 *      "form_field_get_select_options_callback" => callable
	 *  )
	 * )
	 *
	 * See properties definition classes for more specific definition details for each type
	 *
	 * @see \Jet\Config_Definition_Property_Abstract
	 * @see \Jet\Config_Definition_Property_String
	 * @see \Jet\Config_Definition_Property_Bool
	 * @see \Jet\Config_Definition_Property_Int
	 * @see \Jet\Config_Definition_Property_Float
	 * @see \Jet\Config_Definition_Property_Array
	 *
	 *
	 * @var array[]
	 */
	protected static $__config_properties_definition = array();


	/**
	 * Property definition classes names prefix
	 */
	const BASE_PROPERTY_DEFINITION_CLASS_NAME = "Jet\Config_Definition_Property";

	/**
	 * Property/option type - string/text
	 */
	const TYPE_STRING = "String";

	/**
	 * Property/option type - boolean
	 */
	const TYPE_BOOL = "Bool";

	/**
	 * Property/option type - integer
	 */
	const TYPE_INT = "Int";

	/**
	 * Property/option type - floating point number
	 */
	const TYPE_FLOAT = "Float";

	/**
	 * Property/option type - array
	 */
	const TYPE_ARRAY = "Array";


	/**
	 * Property/option type - adapter configuration (sub configuration)
	 */
	const TYPE_ADAPTER_CONFIG = "AdapterConfig";

	/**
	 *
	 * @var string
	 */
	protected $config_file_path = "";

	/**
	 * Ignore non-existent config file and non-existent config section. Usable for installer or setup.
	 *
	 * @var bool
	 */
	protected $soft_mode = false;


	/**
	 * Loaded section data from config
	 *
	 * @var Data_Array
	 */
	protected $_config_data = null;

	/**
	 * File path to application config file (usually JET_APPLICATION_PATH/configs/[JET_APPLICATION_ENVIRONMENT].php)
	 *
	 * @var string
	 */
	protected static $application_config_file_path = "";

	/**
	 * Configuration data (content of config data array wrapped to Data_Array)
	 *
	 * Array key = config file path
	 *
	 * @var Data_Array[]
	 */
	protected static $configs_data = array();


	/**
	 * Sets application config file path
	 *
	 * @static
	 *
	 * @param string $application_config_file_path
	 */
	public static function setApplicationConfigFilePath( $application_config_file_path ) {
		static::$application_config_file_path = $application_config_file_path;
	}

	/**
	 * Gets application config file path
	 *
	 * @static
	 * @return string
	 */
	public static function getApplicationConfigFilePath() {
		return self::$application_config_file_path;
	}

	/**
	 * @param string $config_file_path
	 * @param bool $soft_mode  Ignore non-existent config file and non-existent config section. Usable for installer or setup.
	 */
	public function __construct( $config_file_path, $soft_mode=false ) {
		$this->config_file_path = $config_file_path;
		$this->soft_mode = (bool)$soft_mode;
		$this->setData( $this->readConfigData($config_file_path) );
	}


	/**
	 * @param string $config_file_path
	 *
	 * @throws Config_Exception
	 *
	 * @return Data_Array
	 */
	protected function readConfigData( $config_file_path ) {

		if(!$config_file_path) {
			throw new Config_Exception(
				"Config file path is not defined",
				Config_Exception::CODE_CONFIG_FILE_PATH_NOT_DEFINED
			);
		}

		if(!isset(static::$configs_data[$config_file_path])) {
			if($this->soft_mode && !is_readable($config_file_path)) {
				static::$configs_data[$config_file_path] = new Data_Array( array() );
				return static::$configs_data[$config_file_path];
			}


			$_config_file_path = stream_resolve_include_path( $config_file_path );

			if(!IO_File::isReadable($_config_file_path)) {
				throw new Config_Exception(
					"Config file '{$config_file_path}' does not exist or is not readable",
					Config_Exception::CODE_CONFIG_FILE_IS_NOT_READABLE
				);

			}

			/** @noinspection PhpIncludeInspection */
			$data = require $_config_file_path;
			if(!is_array($data)) {
				throw new Config_Exception(
					"Config file '{$config_file_path}' does not contain PHP array. Example: <?php return array(\"option\" => \"value\"); ",
					Config_Exception::CODE_CONFIG_FILE_IS_NOT_VALID
				);

			}

			static::$configs_data[$config_file_path] = new Data_Array( $data );
		}

		return static::$configs_data[$config_file_path];
	}

	/**
	 *
	 * @see \Jet\Config::$__config_properties_definition for details
	 *
	 * @return array[]
	 */
	protected function getPropertiesDefinitionData() {
		$parent_class = get_parent_class($this);

		$parent_definition = array();
		if(
			$parent_class==__NAMESPACE__."\\Config"  ||
			$parent_class==__NAMESPACE__."\\Config_Module" ||
			$parent_class==__NAMESPACE__."\\Config_Application" ||
			$parent_class==__NAMESPACE__."\\Config_Section" ||
			strpos($parent_class, "_Abstract")!==false
		) {
			/** @noinspection PhpUndefinedVariableInspection */
			if(is_array($parent_class::$__config_properties_definition)) {
				$parent_definition = $parent_class::$__config_properties_definition;
			}
		} else {
			/**
			 * @var Config $parent
			 */
			$parent = new $parent_class();
			$parent_definition = $parent->getPropertiesDefinitionData();
		}


		return array_merge($parent_definition, static::$__config_properties_definition);
	}

	/**
	 *
	 * @return Config_Definition_Property_Abstract[]
	 */
	public function getPropertiesDefinition() {
		$properties = array();
		foreach( $this->getPropertiesDefinitionData() as $property_name=>$property_dd ) {
			$properties[$property_name] = $this->getPropertyDefinitionInstance( $property_name, $property_dd);
		}

		return $properties;
	}

	/**
	 * Get property definition
	 *
	 * @param string $name
	 * @param array $definition_data
	 *
	 * @return Config_Definition_Property_Abstract
	 * @throws Config_Exception
	 */
	protected function getPropertyDefinitionInstance( $name, array $definition_data ) {
		if(!isset($definition_data["type"]) || !$definition_data["type"]) {
			throw new Config_Exception(
				"Property ".get_class($this)."::{$name}: 'type' parameter is not defined ... ",
				Config_Exception::CODE_CONFIG_CHECK_ERROR
			);

		}

		$class_name = static::BASE_PROPERTY_DEFINITION_CLASS_NAME."_".$definition_data["type"];

		unset($definition_data["type"]);

		$instance = new $class_name( $this, $name, $definition_data );

		Factory::checkInstance(static::BASE_PROPERTY_DEFINITION_CLASS_NAME."_Abstract", $instance);

		return $instance;
	}


	/**
	 *
	 * @throws Config_Exception
	 *
	 * @return Data_Array
	 */
	public function getData() {
		return $this->_config_data;
	}


	/**
	 *
	 * @param Data_Array $data
	 *
	 * @throws Config_Exception
	 */
	public function setData(Data_Array $data ) {

		if(static::$__config_data_path) {

			$this_config_data = array();

			if( !$data->exists(static::$__config_data_path) ) {
				if(static::$__config_section_is_obligatory && !$this->soft_mode ) {
					throw new Config_Exception(
						"The obligatory section '".static::$__config_data_path."' is missing in the configuration file {$this->config_file_path}! ",
						Config_Exception::CODE_CONFIG_CHECK_ERROR
					);
				}
			} else {
				$this_config_data = $data->getRaw(static::$__config_data_path);
			}

			$this->_config_data = new  Data_Array($this_config_data);
		} else {
			$this->_config_data = $data;
		}


		$data = $this->_config_data;

		foreach($this->getPropertiesDefinition() as $property_name=>$property_definition) {
			if($property_definition instanceof Config_Definition_Property_AdapterConfig) {
				$this->{$property_name} = $property_definition;

				continue;
			}

			if( $data->exists($property_name)) {
				$this->{$property_name} = $data->getRaw($property_name);
				$property_definition->checkValue( $this->{$property_name} );
			} else {
				if($property_definition->getIsRequired() && !$this->soft_mode  ) {
					throw new Config_Exception(
						"Configuration property ".get_class($this)."::".$property_name." is required by definition, but value is missing!",
						Config_Exception::CODE_CONFIG_CHECK_ERROR
					);
				}
				$this->{$property_name} = $property_definition->getDefaultValue();
			}
		}
	}

	/**
	 * @param string $form_name
	 *
	 * @return Form
	 */
	public function getCommonForm( $form_name="" ) {
		$definition = $this->getPropertiesDefinition();


		$only_properties = array();

		foreach($definition as $property_name => $property) {
			$field = $property->getFormField();

			if(!$field) {
				continue;
			}

			$only_properties[] = $property_name;
		}

		if(!$form_name) {
			$form_name = $this->getClassNameWithoutNamespace();
		}

		return $this->getForm($form_name, $only_properties);
	}

	/**
	 *
	 * @param string $form_name
	 * @param array $only_properties
	 *
	 * @throws DataModel_Exception
	 * @return Form
	 */
	protected function getForm( $form_name, array $only_properties ) {
		$definition = $this->getPropertiesDefinition();

		$fields = array();

		foreach($definition as $property_name=>$property) {
			if( !in_array($property_name, $only_properties) ) {
				continue;
			}

			$field = $property->getFormField();
			if(!$field) {
				$class = get_class($this);

				throw new DataModel_Exception(
					"The property {$class}::{$property} is required for form definition. But property definition ".get_class($property)." prohibits the use of property as form field. ",
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);

			}

			$field->setDefaultValue( $property->getDefaultValue() );

			if($this->_config_data->exists($property_name)) {
				$field->setDefaultValue( $this->{$property_name} );
			}

			$fields[] = $field;
		}

		return new Form( $form_name, $fields );

	}

	/**
	 * @param Form $form
	 *
	 * @param array $data
	 * @param bool $force_catch
	 *
	 * @return bool;
	 */
	public function catchForm( Form $form, $data=null, $force_catch=false   ) {
		if(
			!$form->catchValues($data, $force_catch) ||
			!$form->validateValues()
		) {
			return false;
		}

		$data = $form->getValues();

		$properties = $this->getPropertiesDefinition();

		foreach( $data as $key=>$val ) {
			$field = $form->getField($key);

			$callback = $field->getCatchDataCallback();

			if($callback) {
				$callback( $field->getValueRaw() );
				continue;
			}

			if( !isset($properties[$key]) ) {
				continue;
			}

			$properties[$key]->checkValueType($val);

			$set_method_name = "set".str_replace("_", "", $key);

			if(method_exists($this, $set_method_name)) {
				$this->{$set_method_name}($val);
			} else {
				$this->{$key} = $val;
				if(isset($properties[$key])) {
					$properties[$key]->checkValueType( $this->{$key} );
				}
			}

			$this->_config_data->set($key, $this->{$key});
		}

		return true;
	}

	/**
	 *
	 * @return string
	 */
	public function getConfigFilePath() {
		return $this->config_file_path;
	}

	/**
	 *
	 * @return array
	 */
	public function toArray() {
		$definition = $this->getPropertiesDefinition();

		$result = array();

		foreach($definition as $name=>$def) {
			if(is_object($this->{$name})) {
				/**
				 * @var Config $prop
				 */
				$prop = $this->{$name};
				$result[$name] = $prop->toArray();
			} else {
				$result[$name] = $this->{$name};
			}
		}

		return $result;
	}

	/**
	 * @param string $target_file_path (optional, default: current config_file_path )
	 */
	public function save( $target_file_path=null ) {
		if(!is_readable($this->config_file_path)) {
			$original_data = array();
		} else {
			/** @noinspection PhpIncludeInspection */
			$original_data = require $this->config_file_path;
		}

		$original_data = new Data_Array($original_data);

		$original_data->set(static::$__config_data_path, $this->toArray());

		$config_data = "<?php\n return ".$original_data->export().";";

		if(!$target_file_path) {
			$target_file_path = $this->config_file_path;
		}


		IO_File::write($target_file_path, $config_data);
		static::$configs_data = array();
	}

	/**
	 * @param bool $soft_mode
	 */
	public function setSoftMode( $soft_mode ) {
		$this->soft_mode = (bool)$soft_mode;
	}

	/**
	 * @return boolean
	 */
	public function getSoftMode() {
		return $this->soft_mode;
	}

	/**
	 * @static
	 *
	 * @param $base_directory
	 *
	 * @return array
	 */
	public static function getAvailableHandlersList( $base_directory ) {
		$res = IO_Dir::getSubdirectoriesList($base_directory, "*");
		foreach($res as $path=>$dir) {
			if($dir=="Config") {
				unset($res[$path]);
			}
		}

		return array_combine($res, $res);
	}

}