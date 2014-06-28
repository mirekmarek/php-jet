<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

class Mvc_Router_Map_Cache_Backend_Memcache extends Mvc_Router_Map_Cache_Backend_Abstract {

	/**
	 * @var Mvc_Router_Map_Cache_Backend_Memcache_Config
	 */
	protected $config;

	/**
	 *
	 * @var Memcache_Connection_Abstract
	 */
	private $memcache = null;

	/**
	 * @var string
	 */
	protected $key = '';


	public function initialize() {
		$this->memcache = Memcache::get($this->config->getConnection());

		$this->key = $this->config->getKey();
	}

	/**
	 *
	 * @return  null|Mvc_Router_Map_Abstract
	 */
	public function load() {

		$data = $this->memcache->get( $this->key );
		if(!$data) {
			return null;
		}

		return unserialize($data);
	}

	/**
	 *
	 * @param Mvc_Router_Map_Abstract $item
	 *
	 */
	public function save( Mvc_Router_Map_Abstract $item) {
		$this->memcache->set( $this->key, serialize($item) );
	}


	/**
	 *
	 */
	public function truncate() {
		$this->memcache->delete( $this->key );
	}

	/**
	 * @return string
	 */
	public function helper_getCreateCommand() {
		return '';
	}

	/**
	 *
	 */
	public function helper_create() {
	}


}