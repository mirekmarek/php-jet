<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
class Config_Section extends Config
{

	/**
	 * @var Config
	 */
	protected Config $_config;

	/**
	 * @var ?Config_Definition_Config_Section
	 */
	protected ?Config_Definition_Config_Section $definition = null;



	/**
	 * @param array|null $data
	 */
	public function __construct( array $data = null )
	{
		if( $data !== null ) {
			$this->setData( $data );
		}
	}

	/**
	 * @return Config_Definition_Config_Section
	 */
	public function getDefinition(): Config_Definition_Config_Section
	{
		if( !$this->definition ) {
			$this->definition = Config_Definition::getSectionConfigDefinition( static::class );
		}

		return $this->definition;
	}


	/**
	 * @return Config
	 */
	public function getConfig(): Config
	{
		return $this->_config;
	}

	/**
	 * @param Config $config
	 */
	public function setConfig( Config $config ): void
	{
		$this->_config = $config;
	}

	/**
	 * @return string
	 */
	public function getConfigFilePath(): string
	{
		return $this->getConfig()->getConfigFilePath();
	}

	/**
	 * @param string $config_file_path
	 */
	public function setConfigFilePath( string $config_file_path ): void
	{
		$this->getConfig()->setConfigFilePath( $config_file_path );
	}


	/**
	 *
	 * @return array
	 * @throws Config_Exception
	 *
	 */
	public function readConfigFileData(): array
	{
		return $this->getConfig()->readConfigFileData();
	}


	/**
	 *
	 */
	public function saveConfigFile(): void
	{
		$this->getConfig()->saveConfigFile();
	}

}