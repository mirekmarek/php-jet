<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

	/**
	 * Available annotation:
	 *      Config:
	 * @JetConfig:data_path = '/some/array/path'
	 *              -  Path to configuration data within config file data. @see Data_Array::getRaw() for paths usage explanation
	 *
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
 *
 */
abstract class Config extends BaseObject
{


	/**
	 * Property/option type - string/text
	 */
	const TYPE_STRING = 'String';

	/**
	 * Property/option type - bool
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
	protected $_config_file_path = '';

	/**
	 *
	 * @var Data_Array[]
	 */
	protected static $_config_file_data = [];

	/**
	 *
	 * @var Data_Array
	 */
	protected $_config_data = null;


	/**
	 * Ignore non-existent config file and non-existent config section. Usable for installer or setup.
	 *
	 * @var bool
	 */
	protected $soft_mode = false;

	/**
	 * @var Config_Definition_Config
	 */
	private $definition;

	/**
	 * @var Config_Definition_Property[]
	 */
	private $properties_definition;

	/**
	 * @var string
	 */
	protected static $config_dir_path;


	/**
	 * @return string
	 */
	public static function getConfigDirPath()
	{
		if( !static::$config_dir_path ) {
			static::$config_dir_path = JET_PATH_CONFIG;
		}

		return static::$config_dir_path;
	}

	/**
	 * @param string $path
	 */
	public static function setConfigDirPath( $path )
	{
		static::$config_dir_path = $path;
	}


	/**
	 * @param bool   $soft_mode Ignore non-existent config file and non-existent config section. Usable for installer or setup.
	 */
	public function __construct( $soft_mode = false )
	{

		$this->soft_mode = (bool)$soft_mode;
		$this->setData( $this->readConfigFileData() );
	}

	/**
	 * @return bool
	 */
	public function getSoftMode()
	{
		return $this->soft_mode;
	}

	/**
	 * @param bool $soft_mode
	 */
	public function setSoftMode( $soft_mode )
	{
		$this->soft_mode = (bool)$soft_mode;
	}


	/**
	 *
	 * @param Data_Array|array $data
	 *
	 * @throws Config_Exception
	 */
	public function setData( $data )
	{
		if( !( $data instanceof Data_Array ) ) {
			$data = new Data_Array( $data );
		}

		$definition = $this->getDefinition();

		$config_data_path = $definition->getDataPath();

		if( $config_data_path ) {


			$this_config_data = [];

			if( !$data->exists( $config_data_path ) ) {
				if( !$this->soft_mode ) {
					throw new Config_Exception(
						'The obligatory section \''.$config_data_path.'\' is missing in the configuration file \''.$this->getConfigFilePath().'\'! ',
						Config_Exception::CODE_CONFIG_CHECK_ERROR
					);
				}
			} else {
				$this_config_data = $data->getRaw( $config_data_path );
			}

			$this->_config_data = new  Data_Array( $this_config_data );
		} else {
			$this->_config_data = $data;
		}

		$data = $this->_config_data;

		foreach( $this->getPropertiesDefinition() as $property_name => $property_definition ) {
			if( $property_definition instanceof Config_Definition_Property_ConfigList ) {
				$this->{$property_name} = $property_definition;

				continue;
			}

			if( $data->exists( $property_name ) ) {
				$this->{$property_name} = $data->getRaw( $property_name );
				$property_definition->checkValue( $this->{$property_name} );
			} else {
				if( $property_definition->getIsRequired() && !$this->soft_mode ) {

					throw new Config_Exception(
						'Configuration property '.get_class(
							$this
						).'::'.$property_name.' is required by definition, but value is missing!',
						Config_Exception::CODE_CONFIG_CHECK_ERROR
					);
				}
				$this->{$property_name} = $property_definition->getDefaultValue();
			}
		}
	}

	/**
	 * @return Config_Definition_Config
	 */
	public function getDefinition()
	{
		if( !$this->definition ) {
			$this->definition = Config_Definition::get( get_called_class() );
		}

		return $this->definition;
	}

	/**
	 *
	 * @return Config_Definition_Property[]
	 */
	public function getPropertiesDefinition()
	{
		if( $this->properties_definition!==null ) {
			return $this->properties_definition;
		}

		$definition = $this->getDefinition()->getPropertiesDefinition();

		foreach( $definition as $property ) {
			/**
			 * @var Config_Definition_Property $property
			 */
			$property->setConfiguration( $this );
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
	public function getData()
	{
		return $this->_config_data;
	}

	/**
	 * @param string $form_name
	 *
	 * @return Form
	 */
	public function getCommonForm( $form_name = '' )
	{
		$properties_list = $this->getCommonFormPropertiesList();

		if( !$form_name ) {
			$form_name = str_replace( '\\', '_', get_class( $this ) );
		}

		return $this->getForm( $form_name, $properties_list );
	}

	/**
	 * @return array
	 */
	public function getCommonFormPropertiesList()
	{
		$definition = $this->getPropertiesDefinition();
		$properties_list = [];

		foreach( $definition as $property_name => $property_definition ) {
			if( $property_definition->getFormFieldType()===false ) {
				continue;
			}

			$properties_list[] = $property_name;
		}

		return $properties_list;

	}

	/**
	 *
	 * @param string $form_name
	 * @param array  $properties_list
	 *
	 * @throws DataModel_Exception
	 * @return Form
	 */
	protected function getForm( $form_name, array $properties_list )
	{
		$properties_definition = $this->getPropertiesDefinition();

		$form_fields = [];

		foreach( $properties_list as $property_name ) {

			$property_definition = $properties_definition[$property_name];
			$property = &$this->{$property_name};

			if( ( $field_creator_method_name = $property_definition->getFormFieldCreatorMethodName() ) ) {
				$created_field = $this->{$field_creator_method_name}( $property_definition );
			} else {
				$created_field = $property_definition->createFormField( $property );
			}

			if( !$created_field ) {
				continue;
			}

			$key = $created_field->getName();

			$created_field->setCatcher(
				function( $value ) use ( $property_definition, &$property, $key ) {

					$property_definition->catchFormField( $this, $property, $value );

					$this->_config_data->set( $key, $value );

				}
			);

			$form_fields[] = $created_field;


		}

		return new Form( $form_name, $form_fields );

	}

	/**
	 * @param Form  $form
	 *
	 * @param array $data
	 * @param bool  $force_catch
	 *
	 * @return bool;
	 */
	public function catchForm( Form $form, $data = null, $force_catch = false )
	{

		if(
			!$form->catchInput( $data, $force_catch ) ||
			!$form->validate()
		) {
			return false;
		}

		return $form->catchData();

	}

	/**
	 *
	 * @return array
	 */
	public function toArray()
	{
		$definition = $this->getPropertiesDefinition();

		$result = [];

		foreach( $definition as $name => $def ) {
			if( is_object( $this->{$name} ) ) {
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
	 *
	 * @param string $base_directory
	 *
	 * @return array
	 */
	public static function getAvailableHandlersList( $base_directory )
	{
		$res = IO_Dir::getSubdirectoriesList( $base_directory, '*' );
		foreach( $res as $path => $dir ) {
			if( $dir=='Config' ) {
				unset( $res[$path] );
			}
		}

		return array_combine( $res, $res );
	}






	/**
	 * @return string
	 */
	public function getConfigFilePath()
	{
		if(!$this->_config_file_path) {
			$this->_config_file_path = static::getConfigDirPath().$this->getDefinition()->getName().'.php';
		}

		return $this->_config_file_path;
	}

	/**
	 * @param string $config_file_path
	 */
	public function setConfigFilePath( $config_file_path )
	{
		$this->_config_file_path = $config_file_path;
	}


	/**
	 *
	 * @throws Config_Exception
	 *
	 * @return Data_Array
	 */
	public function readConfigFileData()
	{
		$config_file_path = $this->getConfigFilePath();

		if(!isset(Config::$_config_file_data[$config_file_path])) {

			if( !IO_File::isReadable( $config_file_path ) ) {
				if( $this->soft_mode ) {
					Config::$_config_file_data[$config_file_path] = new Data_Array( [] );

					return Config::$_config_file_data[$config_file_path];
				}

				throw new Config_Exception(
					'Config file \''.$config_file_path.'\' does not exist or is not readable',
					Config_Exception::CODE_CONFIG_FILE_IS_NOT_READABLE
				);

			}

			/** @noinspection PhpIncludeInspection */
			$data = require $config_file_path;
			if( !is_array( $data ) ) {
				throw new Config_Exception(
					'Config file \''.$config_file_path.'\' does not contain PHP array. Example: <?php return array(\'option\' => \'value\'); ',
					Config_Exception::CODE_CONFIG_FILE_IS_NOT_VALID
				);

			}

			Config::$_config_file_data[$config_file_path] = new Data_Array( $data );
		}

		return Config::$_config_file_data[$config_file_path];
	}


	/**
	 *
	 */
	public function writeConfigFile()
	{
		$config_file_path = $this->getConfigFilePath();

		$data = new Data_Array( $this->toArray() );

		$config_data = '<?php'.JET_EOL.'return '.$data->export();

		IO_File::write( $config_file_path, $config_data );

		Config::$_config_file_data = [];
	}

}