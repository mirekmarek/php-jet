<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
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
 *              is_required: true,
 *
 */


/**
 *
 */
abstract class Config extends BaseObject implements Form_Definition_Interface
{
	use Form_Definition_Trait;

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
		if( $this->properties_definition === null ) {
			$this->properties_definition = $this->getDefinition()->getPropertiesDefinition();
		}

		return $this->properties_definition;
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