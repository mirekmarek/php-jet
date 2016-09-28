<?php
/**
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Redis
 */
namespace Jet;

class Redis_Connection extends \Redis implements BaseObject_Interface {

	use BaseObject_Trait;
	use BaseObject_Trait_MagicSleep;
	//use Object_Trait_MagicGet;
	//use Object_Trait_MagicSet;
	use BaseObject_Trait_MagicClone;

	/**
	 *
	 * @var Redis_Connection_Config
	 */
	protected $config = null;

	/**
	 * @param Redis_Connection_Config $config
	 *
	 * @throws Redis_Exception
	 */
	public function __construct( Redis_Connection_Config $config ) {

		$this->config = $config;

		parent::__construct();

		if(!$this->connect( $this->config->getHost(), $this->config->getPort() )) {
			throw new Redis_Exception(
				'Unable to connect Redis \''.$this->config->getHost().':'.$this->config->getPort().'\' ',
				Redis_Exception::CODE_UNABLE_TO_CONNECT
			);
		}
	}

	/**
	 * Close connection on exit
	 */
	public function __destruct() {
		try {
			$this->disconnect();
		} catch(Exception $e){}
	}

	/**
	 *
	 * @return Redis_Connection_Config
	 */
	public function getConfig(){
		return $this->config;
	}

	/**
	 *
	 * @param string $key
	 *
	 */
	public function __get( $key ) {
	}

	/**
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function __set( $key, $value ) {
		$this->{$key} = $value;
	}

	/**
	 *
	 */
	public function disconnect() {
		$this->close();
	}
}