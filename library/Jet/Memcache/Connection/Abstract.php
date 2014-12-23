<?php
/**
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Memcache
 */
namespace Jet;

/**
 * Class Memcache_Connection_Abstract
 *
 * @JetFactory:class = null
 * @JetFactory:method = null
 * @JetFactory:mandatory_parent_class = 'Jet\Memcache_Connection_Abstract'
 */
abstract class Memcache_Connection_Abstract extends \Memcache implements Object_Interface {

	use Object_Trait;
	use Object_Trait_MagicSleep;
	//use Object_Trait_MagicGet;
	//use Object_Trait_MagicSet;
	use Object_Trait_MagicClone;

	/**
	 *
	 * @var Memcache_Connection_Config_Abstract
	 */
	protected $config = null;

	/**
	 * @param Memcache_Connection_Config_Abstract $config
	 *
	 * @throws Memcache_Exception
	 */
	public function __construct( Memcache_Connection_Config_Abstract $config ) {

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
	 * @return Memcache_Connection_Config_Abstract
	 */
	public function getConfig(){
		return $this->config;
	}

	/**
	 *
	 */
	abstract public function disconnect();

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

}