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


	/** @noinspection PhpMissingParentConstructorInspection */
	/**
	 * @param array|null $data
	 */
	public function __construct( array $data = null )
	{
		if($data!==null) {
			$this->setData( $data );
		}
	}

	/**
	 * @return Config_Definition_Config
	 */
	public function getDefinition() : Config_Definition_Config
	{
		if( !$this->definition ) {
			$this->definition = Config_Definition::getSectionConfigDefinition( get_called_class() );
		}

		return $this->definition;
	}


	/**
	 * @return Config
	 */
	public function getConfig() : Config
	{
		return $this->_config;
	}

	/**
	 * @param Config $config
	 */
	public function setConfig( Config $config ) : void
	{
		$this->_config = $config;
	}

	/**
	 * @return string
	 */
	public function getConfigFilePath() : string
	{
		return $this->getConfig()->getConfigFilePath();
	}

	/**
	 * @param string $config_file_path
	 */
	public function setConfigFilePath( string $config_file_path ) : void
	{
		$this->getConfig()->setConfigFilePath( $config_file_path );
	}


	/**
	 *
	 * @throws Config_Exception
	 *
	 * @return array
	 */
	public function readConfigFileData() : array
	{
		return $this->getConfig()->readConfigFileData();
	}


	/**
	 *
	 */
	public function writeConfigFile() : void
	{
		$this->getConfig()->writeConfigFile();
	}

}