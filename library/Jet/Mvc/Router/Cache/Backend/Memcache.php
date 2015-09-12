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

class Mvc_Router_Cache_Backend_Memcache extends Mvc_Router_Cache_Backend_Abstract {
	const KEYS_LIST_KEY = '__keys__';

	/**
	 * @var Mvc_Router_Cache_Backend_Memcache_Config
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
	protected $key_prefix = '';


	public function initialize() {
		$this->memcache = Memcache::get($this->config->getConnection());

		$this->key_prefix = $this->config->getKeyPrefix().':';
	}

	/**
	 * @param string $URL
	 *
	 * @return string
	 */
	protected function getCacheKey( $URL ) {
		return $this->key_prefix.md5($URL);
	}

	/**
	 * Get cache item for given URL or null if does not exist
	 *
	 *
	 * @param string $URL
	 *
	 * @return  null|array
	 */
	public function load($URL) {

		$data = $this->memcache->get( $this->getCacheKey($URL) );
		if(!$data) {
			return null;
		}

		return unserialize($data);
	}

	/**
	 *
	 * @param string $URL
	 * @param array $item
	 *
	 */
	public function save($URL, array $item) {
		$key = $this->getCacheKey($URL);
		$this->memcache->set( $key, serialize($item) );
		$this->storeKey($key);

	}

	/**
	 * Truncate cache. URL can be:
	 *
	 * null - total cache truncate
	 * string - delete record for specified URL
	 * array - delete records for specified URLs
	 *
	 * @param null|string|string[] $URL
	 *
	 */
	public function truncate($URL = null) {
		if($URL===null) {
			$list = $this->getKeysList();

			foreach( $list as $key ) {
				$this->memcache->delete( $key );
			}

		} else {
			$this->memcache->delete( $this->getCacheKey($URL) );
		}
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

	/**
	 * @param $key
	 */
	protected function storeKey( $key ) {

		$list = $this->getKeysList();

		if(!in_array($key, $list)) {
			$list[] = $key;

			$list_key = $this->key_prefix.static::KEYS_LIST_KEY;
			$this->memcache->set( $list_key, serialize($list) );
		}
	}

	/**
	 * @return array
	 */
	protected function getKeysList() {
		$list_key = $this->key_prefix.static::KEYS_LIST_KEY;

		$list = $this->memcache->get( $list_key );
		if(!$list) {
			$list = array();
		} else {
			$list = unserialize($list);
		}

		return $list;
	}

}