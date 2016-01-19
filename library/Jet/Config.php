<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Config
 */
namespace Jet;

/**
 * Available annotation:
 *      Config:
 *          @JetConfig:data_path = '/some/array/path'
 *              -  Path to configuration data within config file data. @see Data_Array::getRaw() for paths usage explanation
 *
 *          @JetConfig:section_is_obligatory = false
 *              - If defined section does not exists in the configuration data and this options is true then Config system throws exception. Default is true
 *
 *      Config Property Definition:
 *          /**
 *           * @JetConfig:type = Config::TYPE_*,
 *           * @JetConfig:description = 'Some description ...',
 *           * @JetConfig:is_required = true
 *           * @JetConfig:default_value = 'some default value'
 *           * @JetConfig:form_field_type = Form::TYPE_*
 *           *     - (optional, default: autodetect)
 *           * @JetConfig:form_field_label = 'Some form filed label:'
 *           * @JetConfig:form_field_options = ['option1' => 'Option 1', 'option2' => 'Option 1', 'option3'=>'Option 3' ]
 *           *      - optional
 *           * @JetConfig:form_field_error_messages = ['error_code' => 'Message' ]
 *           * @JetConfig:form_field_get_select_options_callback = callable
 *           *     - optional
 *           *
 *           * @var string          //some PHP type ...
 *           * /
 *          protected $some_property;
 *
 */


/**
 * Class Config
 *
 */
abstract class Config extends Object implements Object_Reflection_ParserInterface {



	/**
	 * Property/option type - string/text
	 */
	const TYPE_STRING = 'String';

	/**
	 * Property/option type - boolean
	 */
	const TYPE_BOOL = 'Bool';

	/**
	 * Property/option type - integer
	 */
	const TYPE_INT = 'Int';

	/**
	 * Property/option type - floating point number
	 */
	const TYPE_FLOAT = 'Float';

	/**
	 * Property/option type - array
	 */
	const TYPE_ARRAY = 'Array';


	/**
	 * Property/option type - list of configurations (sub configuration)
	 */
	const TYPE_CONFIG_LIST = 'ConfigList';

	/**
	 *
	 * @var string
	 */
	protected $config_file_path = '';

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
	 * Configuration data (content of config data array wrapped to Data_Array)
	 *
	 * Array key = config file path
	 *
	 * @var Data_Array[]
	 */
	protected static $configs_data = [];

	/**
	 * @var Config_Definition_Config
	 */
	private $definition;

	/**
	 * @var Config_Definition_Property_Abstract
	 */
	private $properties_definition;


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
				'Config file path is not defined',
				Config_Exception::CODE_CONFIG_FILE_PATH_NOT_DEFINED
			);
		}

		if(!isset(static::$configs_data[$config_file_path])) {
			$_config_file_path = stream_resolve_include_path( $config_file_path );


			if(!IO_File::isReadable($_config_file_path)) {
				if($this->soft_mode) {
					static::$configs_data[$config_file_path] = new Data_Array( []);
					return static::$configs_data[$config_file_path];

				}

				throw new Config_Exception(
					'Config file \''.$config_file_path.'\' does not exist or is not readable',
					Config_Exception::CODE_CONFIG_FILE_IS_NOT_READABLE
				);

			}

			/** @noinspection PhpIncludeInspection */
			$data = require $_config_file_path;
			if(!is_array($data)) {
				throw new Config_Exception(
					'Config file \''.$config_file_path.'\' does not contain PHP array. Example: <?php return array(\'option\' => \'value\'); ',
					Config_Exception::CODE_CONFIG_FILE_IS_NOT_VALID
				);

			}

			static::$configs_data[$config_file_path] = new Data_Array( $data );
		}

		return static::$configs_data[$config_file_path];
	}

	/**
	 * @return Config_Definition_Config
	 */
	public function getDefinition() {
		if(!$this->definition) {
			$this->definition = Config_Definition_Config::getDefinition( get_called_class() );
		}

		return $this->definition;
	}

	/**
	 *
	 * @return Config_Definition_Property_Abstract[]
	 */
	public function getPropertiesDefinition() {
		if($this->properties_definition!==null) {
			return $this->properties_definition;
		}

		$definition = $this->getDefinition()->getPropertiesDefinition();

		foreach( $definition as $property ) {
			/**
			 * @var Config_Definition_Property_Abstract $property
			 */
			$property->setConfiguration($this);
		}

		$this->properties_definition = $definition;

		return $definition;
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
	 * @param Data_Array|array $data
	 * @param bool $use_data_path_for_source_data
	 *
	 * @throws Config_Exception
	 */
	public function setData( $data, $use_data_path_for_source_data=true ) {
		if( !($data instanceof Data_Array) ) {
			$data = new Data_Array( $data );
		}

		$definition = $this->getDefinition();

		$config_data_path = $definition->getDataPath();

		if(
			$config_data_path &&
			$use_data_path_for_source_data
		) {

			$config_section_is_obligatory = $definition->getSectionIsObligatory();

			$this_config_data = [];

			if( !$data->exists($config_data_path) ) {
				if(
					$config_section_is_obligatory &&
					!$this->soft_mode
				) {
					throw new Config_Exception(
						'The obligatory section \''.$config_data_path.'\' is missing in the configuration file \''.$this->config_file_path.'\'! ',
						Config_Exception::CODE_CONFIG_CHECK_ERROR
					);
				}
			} else {
				$this_config_data = $data->getRaw($config_data_path);
			}

			$this->_config_data = new  Data_Array($this_config_data);
		} else {
			$this->_config_data = $data;
		}

		$data = $this->_config_data;

		foreach($this->getPropertiesDefinition() as $property_name=>$property_definition) {
			if($property_definition instanceof Config_Definition_Property_ConfigList) {
				$this->{$property_name} = $property_definition;

				continue;
			}

			if( $data->exists($property_name)) {
				$this->{$property_name} = $data->getRaw($property_name);
				$property_definition->checkValue( $this->{$property_name} );
			} else {
				if($property_definition->getIsRequired() && !$this->soft_mode  ) {
					throw new Config_Exception(
						'Configuration property '.get_class($this).'::'.$property_name.' is required by definition, but value is missing!',
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
	public function getCommonForm( $form_name='' ) {

		$properties_list = $this->getCommonFormPropertiesList();


		if(!$form_name) {
			$form_name = str_replace( '\\','_', get_class($this) );
		}

		return $this->getForm($form_name, $properties_list);
	}

	/**
	 * @return array
	 */
	public function getCommonFormPropertiesList() {
		$definition = $this->getPropertiesDefinition();
		$properties_list = [];

		foreach($definition as $property_name => $property_definition) {
			if(
				$property_definition->getFormFieldType()===false
			) {
				continue;
			}

			$properties_list[] = $property_name;
		}

		return $properties_list;

	}


	/**
	 *
	 * @param string $form_name
	 * @param array $properties_list
	 *
	 * @throws DataModel_Exception
	 * @return Form
	 */
	protected function getForm( $form_name, array $properties_list ) {
		$properties_definition = $this->getPropertiesDefinition();

		$form_fields = [];

		foreach( $properties_list as $property_name ) {

			$property_definition = $properties_definition[$property_name];
			$property = $this->{$property_name};

			if( ($field_creator_method_name = $property_definition->getFormFieldCreatorMethodName()) ) {
				$created_field = $this->{$field_creator_method_name}( $property_definition );
			} else {
				$created_field = $property_definition->createFormField( $property );
			}

			if(!$created_field) {
				continue;
			}

			if(is_array($created_field)) {
				foreach( $created_field as $f ) {
					$form_fields[] = $f;
				}
			} else {
				$form_fields[] = $created_field;
			}


		}

		return new Form( $form_name, $form_fields );

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

			if( !isset($properties[$key]) ) {
				continue;
			}


			$properties[$key]->checkValueType($val);

			$setter_method_name = $this->getSetterMethodName( $key );

			if(method_exists($this, $setter_method_name)) {
				$this->{$setter_method_name}($val);
			} else {
				$this->{$key} = $val;
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

		$result = [];

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
			$original_data = [];
		} else {
			/** @noinspection PhpIncludeInspection */
			$original_data = require $this->config_file_path;
		}

		$original_data = new Data_Array($original_data);

		$config_data_path = $this->getDefinition()->getDataPath();

		$original_data->set( $config_data_path, $this->toArray());

		$config_data = '<?php'.JET_EOL.'return '.$original_data->export();

		if(!$target_file_path) {
			$target_file_path = $this->config_file_path;
		}


		try {
			IO_File::write($target_file_path, $config_data);
		} catch(Exception $e) {}
		static::$configs_data = [];
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
		$res = IO_Dir::getSubdirectoriesList($base_directory, '*');
		foreach($res as $path=>$dir) {
			if($dir=='Config') {
				unset($res[$path]);
			}
		}

		return array_combine($res, $res);
	}


	/**
	 * @param array $reflection_data
	 * @param $class_name
	 * @param string $key
	 * @param string $definition
	 * @param mixed $value
	 *
	 * @throws Object_Reflection_Exception
	 */
	public static function parseClassDocComment(&$reflection_data, $class_name, $key, $definition, $value) {
		Config_Definition_Config::parseClassDocComment( $reflection_data, $class_name, $key, $definition, $value );
	}

	/**
	 * @param array &$reflection_data
	 * @param string $class_name
	 * @param string $property_name
	 * @param string $key
	 * @param string $definition
	 * @param mixed $value
	 *
	 */
	public static function parsePropertyDocComment(&$reflection_data, $class_name, $property_name, $key, $definition, $value) {
		Config_Definition_Config::parsePropertyDocComment( $reflection_data,$class_name, $property_name, $key, $definition, $value );
	}

}