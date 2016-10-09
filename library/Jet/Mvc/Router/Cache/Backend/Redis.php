<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

class Mvc_Router_Cache_Backend_Redis extends Mvc_Router_Cache_Backend_Abstract {

	/**
	 * @var Mvc_Router_Cache_Backend_Redis_Config
	 */
	protected $config;

	/**
	 *
	 * @var Redis_Connection
	 */
	private $redis = null;

	/**
	 * @var string
	 */
	protected $key_prefix = '';


	public function initialize() {
		$this->redis = Redis::get($this->config->getConnection());

		$this->key_prefix = $this->config->getKeyPrefix().':';
	}

	/**
	 * @param string $URL
	 *
	 * @return string
	 */
	protected function getCacheKey( $URL ) {
		return md5($URL);
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

		$data = $this->redis->get( $this->getCacheKey($URL) );
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
		$this->redis->set( $this->getCacheKey($URL), serialize($item) );

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
			$pattern = $this->key_prefix;
			$pattern .= '*';

			/** @noinspection PhpVoidFunctionResultUsedInspection */
			$keys = $this->redis->getKeys($pattern);

			foreach( $keys as $key ) {
				$this->redis->delete( $key );
			}

		} else {
			$this->redis->delete( $this->getCacheKey($URL) );
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

}