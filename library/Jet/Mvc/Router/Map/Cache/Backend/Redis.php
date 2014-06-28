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

class Mvc_Router_Map_Cache_Backend_Redis extends Mvc_Router_Map_Cache_Backend_Abstract {

	/**
	 * @var Mvc_Router_Map_Cache_Backend_Redis_Config
	 */
	protected $config;

	/**
	 *
	 * @var Redis_Connection_Abstract
	 */
	private $redis = null;

	/**
	 * @var string
	 */
	protected $key = '';


	public function initialize() {
		$this->redis = Redis::get($this->config->getConnection());

		$this->key = $this->config->getKey();
	}

	/**
     *
	 * @return  null|Mvc_Router_Map_Abstract
	 */
	public function load() {

		$data = $this->redis->get( $this->key );
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
		$this->redis->set( $this->key, serialize($item) );

	}

	/**
	 * Truncate cache
	 */
	public function truncate() {
		$this->redis->delete( $this->key );
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