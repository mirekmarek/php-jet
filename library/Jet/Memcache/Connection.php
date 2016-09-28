<?php
/**
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mamcache
 */
namespace Jet;

class Memcache_Connection extends \Memcache implements BaseObject_Interface {
	use BaseObject_Trait;
	use BaseObject_Trait_MagicSleep;
	//use Object_Trait_MagicGet;
	//use Object_Trait_MagicSet;
	use BaseObject_Trait_MagicClone;

	/**
	 *
	 * @var Memcache_Connection_Config
	 */
	protected $config = null;

	/**
	 * @param Memcache_Connection_Config $config
	 *
	 * @throws Memcache_Exception
	 */
	public function __construct( Memcache_Connection_Config $config ) {

		$this->config = $config;

		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		if(!@$this->connect( $this->config->getHost(), $this->config->getPort() )) {
			throw new Memcache_Exception(
				'Unable to connect Memcache \''.$this->config->getHost().':'.$this->config->getPort().'\' ',
				Memcache_Exception::CODE_UNABLE_TO_CONNECT
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
	 * @return Memcache_Connection_Config
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