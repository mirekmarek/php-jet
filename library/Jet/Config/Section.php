<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected $_config;

	/**
	 * @var Config_Definition_Config_Section
	 */
	protected $definition;


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
	public function getDefinition()
	{
		if( !$this->definition ) {
			$this->definition = Config_Definition::getSectionConfigDefinition( get_called_class() );
		}

		return $this->definition;
	}


	/**
	 * @return Config
	 */
	public function getConfig()
	{
		return $this->_config;
	}

	/**
	 * @param Config $config
	 */
	public function setConfig( Config $config )
	{
		$this->_config = $config;
	}

	/**
	 * @return string
	 */
	public function getConfigFilePath()
	{
		return $this->getConfig()->getConfigFilePath();
	}

	/**
	 * @param string $config_file_path
	 */
	public function setConfigFilePath( $config_file_path )
	{
		$this->getConfig()->setConfigFilePath( $config_file_path );
	}


	/**
	 *
	 * @throws Config_Exception
	 *
	 * @return array
	 */
	public function readConfigFileData()
	{
		return $this->getConfig()->readConfigFileData();
	}


	/**
	 *
	 */
	public function writeConfigFile()
	{
		$this->getConfig()->writeConfigFile();
	}

}