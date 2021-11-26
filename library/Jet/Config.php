<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 * Available attributes:
 *
 * Class:
 * #[Config_Definition(name: 'some_name')]
 *
 *
 * Properties:
 *           #[Config_Definition(
 *              type: Config::TYPE_*,
 *
 *              description: 'Some description ...',
 *              is_required: true,
 *
 *
 *              form_field_type: Form::TYPE_*,
 *                  - (optional, default: autodetect)
 *              form_field_label: 'Some form filed label:',
 *              form_field_options: ['option1' => 'Option 1', 'option2' => 'Option 1', 'option3'=>'Option 3' ]
 *                  - optional
 *              form_field_error_messages: [Form_Field_*::ERROR_CODE_* => 'Message' ]
 *              form_field_get_select_options_callback: callable
 *                  - optional
 *             )]
 *
 */


/**
 *
 */
abstract class Config extends BaseObject
{

	const TYPE_STRING = 'String';
	const TYPE_BOOL = 'Bool';
	const TYPE_INT = 'Int';
	const TYPE_FLOAT = 'Float';
	const TYPE_ARRAY = 'Array';
	const TYPE_SECTION = 'Section';
	const TYPE_SECTIONS = 'Sections';


	/**
	 * @var bool
	 */
	protected static bool $be_tolerant = false;

	/**
	 *
	 * @var array
	 */
	protected static array $_config_file_data = [];

	/**
	 *
	 * @var string
	 */
	protected string $_config_file_path = '';

	/**
	 * @var Config_Definition_Config|null
	 */
	private Config_Definition_Config|null $definition = null;

	/**
	 * @var Config_Definition_Property[]
	 */
	private array|null $properties_definition = null;


	/**
	 * @return bool
	 */
	public static function beTolerant(): bool
	{
		return self::$be_tolerant;
	}

	/**
	 * @param bool $be_tolerant
	 */
	public static function setBeTolerant( bool $be_tolerant ): void
	{
		self::$be_tolerant = $be_tolerant;
	}


	/**
	 * @param ?array $data
	 */
	public function __construct( ?array $data = null )
	{
		if( $data === null ) {
			$data = $this->readConfigFileData();
		}

		$this->setData( $data );
	}


	/**
	 *
	 * @param array $data
	 *
	 * @throws Config_Exception
	 */
	public function setData( array $data ): void
	{

		foreach( $this->getPropertiesDefinition() as $property_name => $property_definition ) {

			if( !array_key_exists( $property_name, $data ) ) {

				if(
					$property_definition->getIsRequired() &&
					!static::beTolerant()
				) {

					throw new Config_Exception(
						'Configuration property ' . get_class( $this ) . '::' . $property_name . ' is required by definition, but value is missing!',
						Config_Exception::CODE_CONFIG_CHECK_ERROR
					);
				}

				continue;
			}

			$this->{$property_name} = $property_definition->prepareValue( $data[$property_name], $this );
		}
	}

	/**
	 * @return Config_Definition_Config
	 */
	public function getDefinition(): Config_Definition_Config
	{
		if( !$this->definition ) {
			$this->definition = Config_Definition::getMainConfigDefinition( static::class );
		}

		return $this->definition;
	}

	/**
	 *
	 * @return Config_Definition_Property[]
	 */
	public function getPropertiesDefinition(): array
	{
		if( $this->properties_definition !== null ) {
			return $this->properties_definition;
		}

		$definition = $this->getDefinition()->getPropertiesDefinition();

		foreach( $definition as $property ) {
			$property->setConfiguration( $this );
		}

		$this->properties_definition = $definition;

		return $definition;
	}


	/**
	 * @param string $form_name
	 *
	 * @return Form
	 */
	public function getCommonForm( string $form_name = '' ): Form
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
	public function getCommonFormPropertiesList(): array
	{
		$definition = $this->getPropertiesDefinition();
		$properties_list = [];

		foreach( $definition as $property_name => $property_definition ) {
			if( $property_definition->getFormFieldType() === false ) {
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
	 * @return Form
	 * @throws DataModel_Exception
	 */
	protected function getForm( string $form_name, array $properties_list ): Form
	{
		$properties_definition = $this->getPropertiesDefinition();

		$form_fields = [];

		foreach( $properties_list as $property_name ) {

			$property_definition = $properties_definition[$property_name];
			$property = &$this->{$property_name};


			if( ($field_creator_method_name = $property_definition->getFormFieldCreatorMethodName()) ) {
				$created_field = $this->{$field_creator_method_name}( $property_definition );
			} else {
				$created_field = $property_definition->createFormField( $property );
			}

			if( !$created_field ) {
				continue;
			}


			if( is_array( $created_field ) ) {

				foreach( $created_field as $field ) {
					$form_fields[] = $field;
				}

			} else {
				$created_field->setCatcher(
					function( $value ) use ( $property_definition, &$property ) {
						$property_definition->catchFormField( $this, $property, $value );
					}
				);

				$form_fields[] = $created_field;

			}


		}

		return new Form( $form_name, $form_fields );
	}

	/**
	 *
	 * @return array
	 */
	public function toArray(): array
	{
		$definition = $this->getPropertiesDefinition();

		$result = [];

		foreach( $definition as $name => $def ) {
			if( is_array( $this->{$name} ) ) {
				$result[$name] = [];

				foreach( $this->{$name} as $k => $v ) {
					if( is_object( $v ) ) {
						/**
						 * @var Config $v
						 */
						$result[$name][$k] = $v->toArray();
					} else {
						$result[$name][$k] = $v;
					}
				}

			} else {
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
		}

		return $result;
	}


	/**
	 * @return string
	 */
	public function getConfigFilePath(): string
	{
		if( !$this->_config_file_path ) {
			$this->_config_file_path = SysConf_Path::getConfig() . $this->getDefinition()->getName() . '.php';
		}

		return $this->_config_file_path;
	}

	/**
	 * @param string $config_file_path
	 */
	public function setConfigFilePath( string $config_file_path ): void
	{
		$this->_config_file_path = $config_file_path;
	}


	/**
	 *
	 * @return array
	 * @throws Config_Exception
	 *
	 */
	public function readConfigFileData(): array
	{
		$config_file_path = $this->getConfigFilePath();

		if( !isset( Config::$_config_file_data[$config_file_path] ) ) {

			if( !IO_File::isReadable( $config_file_path ) ) {
				if( static::beTolerant() ) {
					Config::$_config_file_data[$config_file_path] = [];

					return Config::$_config_file_data[$config_file_path];
				}

				throw new Config_Exception(
					'Config file \'' . $config_file_path . '\' does not exist or is not readable',
					Config_Exception::CODE_CONFIG_FILE_IS_NOT_READABLE
				);

			}

			$data = require $config_file_path;
			if( !is_array( $data ) ) {
				throw new Config_Exception(
					'Config file \'' . $config_file_path . '\' does not contain PHP array. Example: <?php return [\'option\' => \'value\']; ',
					Config_Exception::CODE_CONFIG_FILE_IS_NOT_VALID
				);

			}

			Config::$_config_file_data[$config_file_path] = $data;
		}

		return Config::$_config_file_data[$config_file_path];
	}


	/**
	 *
	 */
	public function saveConfigFile(): void
	{
		IO_File::writeDataAsPhp(
			$this->getConfigFilePath(),
			$this->toArray()
		);

		Config::$_config_file_data = [];
	}

}