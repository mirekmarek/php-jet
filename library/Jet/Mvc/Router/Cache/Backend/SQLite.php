<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

class Mvc_Router_Cache_Backend_SQLite extends Mvc_Router_Cache_Backend_Abstract {

	/**
	 * @var Mvc_Router_Cache_Backend_SQLite_Config
	 */
	protected $config;

	/**
	 *
	 * @var Db_Connection_Abstract
	 */
	private $_db = null;

	/**
	 * @var string
	 */
	protected $_table_name = '';


	/**
	 * Initializes the cache backend
	 *
	 */
	public function initialize() {
		$this->_db = Db::create('mvc_router_cache_sqlite_connection', array(
			'name' => 'mvc_router_cache_sqlite_connection',
			'driver' => DB::DRIVER_SQLITE,
			'DSN' => $this->config->getDSN()
		));

		$this->_table_name = $this->config->getTableName();
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

		$data = $this->_db->fetchOne('SELECT `data` FROM `'.$this->_table_name.'`
				WHERE
					`URL_hash`=:URL_hash',
			array(
				'URL_hash' => md5($URL)

			)
		);
		if(!$data) {
			return null;
		}

		$data = $this->unserialize($data);
		if(!$data) {
			$this->truncate();
			return null;
		}

		return $data;
	}

	/**
	 *
	 * @param string $URL
	 * @param array $item
	 *
	 */
	public function save($URL, array $item) {
		$data = array(
			'URL' => $URL,
			'URL_hash' => md5($URL),
			'data' => $this->serialize($item),
		);

		$this->_db->execCommand('INSERT OR IGNORE INTO `'.$this->_table_name.'` (
								`URL`,
								`URL_hash`,
								`data`,
								`created_date_time`
							) VALUES (
								:URL,
								:URL_hash,
								:data,
								datetime(\'now\')

							)',$data);

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
			$this->_db->execCommand('DELETE FROM `'.$this->_table_name.'`');
		} else {
			if(is_array($URL)) {
				foreach($URL as $_URL) {
					$this->_db->execCommand('DELETE FROM `'.$this->_table_name.'` WHERE `URL_hash`=:URL_hash',
						array(
							'URL_hash' => md5($_URL),
						));
				}
			} else {
				$this->_db->execCommand('DELETE FROM `'.$this->_table_name.'` WHERE `URL_hash`=:URL_hash',
					array(
						'URL_hash' => md5($URL),
					));

			}
		}
	}

	/**
	 * @return string
	 */
	public function helper_getCreateCommand() {
		return 'CREATE TABLE IF NOT EXISTS `'.$this->_table_name.'` ('.JET_EOL
			.JET_TAB.' `URL` TEXT,'.JET_EOL
			.JET_TAB.' `URL_hash` TEXT,'.JET_EOL
			.JET_TAB.' `data` BLOB,'.JET_EOL
			.JET_TAB.' `created_date_time` NUMERIC,'.JET_EOL
			.JET_TAB.' PRIMARY KEY (`URL_hash`)'.JET_EOL
			.JET_TAB.')';
	}

	/**
	 *
	 */
	public function helper_create() {
		$this->_db->execCommand( $this->helper_getCreateCommand() );
	}

	/**
	 * @param $data
	 *
	 * @return string
	 */
	protected function serialize( $data ) {
		return base64_encode( serialize($data) );
	}

	/**
	 * @param $string
	 *
	 * @return mixed
	 */
	protected function unserialize( $string ) {
		$data = base64_decode($string);
		return unserialize($data);
	}

}