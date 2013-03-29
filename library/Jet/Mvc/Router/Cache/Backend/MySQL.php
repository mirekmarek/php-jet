<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Router
 */
namespace Jet;

class Mvc_Router_Cache_Backend_MySQL extends Mvc_Router_Cache_Backend_Abstract {

	/**
	 * @var Mvc_Router_Cache_Backend_MySQL_Config
	 */
	protected $config;

	/**
	 *
	 * @var Db_Adapter_Abstract
	 */
	private $_db_read = NULL;
	/**
	 *
	 * @var Db_Adapter_Abstract
	 */
	private $_db_write = NULL;

	/**
	 * @var string
	 */
	protected $_table_name = "";


	/**
	 * Initializes the cache backend
	 *
	 *
	 * @return void
	 */
	public function initialize() {
		$this->_db_read = Db::get($this->config->getConnectionRead());
		$this->_db_write = Db::get($this->config->getConnectionWrite());
		$this->_table_name = $this->config->getTableName();
	}

	/**
	 * Get cache item for given URL or null if does not exist
	 *
	 *
	 * @param string $URL
	 *
	 * @return  null|Mvc_Router_Abstract
	 */
	public function load($URL) {

		$data = $this->_db_read->fetchOne("SELECT `data` FROM `{$this->_table_name}`
				WHERE
					`URL_hash`=:URL_hash",
			array(
				"URL_hash" => md5($URL)

			)
		);
		if(!$data) {
			return null;
		}

		return unserialize($data);
	}

	/**
	 *
	 * @param string $URL
	 * @param Mvc_Router_Abstract $item
	 *
	 * @return void
	 */
	public function save($URL, Mvc_Router_Abstract $item) {
		$data = array(
			"URL" => $URL,
			"URL_hash" => md5($URL),
			"data" => serialize($item),
		);

		$this->_db_write->query("INSERT IGNORE INTO `{$this->_table_name}` SET
					`URL`=:URL,
					`URL_hash`=:URL_hash,
					`data`=:data,
					`created_date_time`=NOW()
				",$data);

	}

	/**
	 * Truncate cache. URL can be:
	 *
	 * null - total cache truncate
	 * string - delete record for specified URL
	 * array - delete records for specified URLs
	 *
	 * @param null|string|string[] $URL
	 * @return void
	 */
	public function truncate($URL = null) {
		if($URL===null) {
			$this->_db_write->query("TRUNCATE TABLE `{$this->_table_name}`");
		} else {
			if(is_array($URL)) {
				foreach($URL as $_URL) {
					$this->_db_write->query("DELETE FROM `{$this->_table_name}` WHERE `URL_hash`=:URL_hash",
						array(
							"URL_hash" => md5($_URL),
						));
				}
			} else {
				$this->_db_write->query("DELETE FROM `{$this->_table_name}` WHERE `URL_hash`=:URL_hash",
					array(
						"URL_hash" => md5($URL),
					));

			}
		}

	}

	/**
	 * @return string
	 */
	public function helper_getCreateCommand() {
		$engine = $this->config->getEngine();

		return "CREATE TABLE IF NOT EXISTS `{$this->_table_name}` (\n"
			."\t `URL` varchar(65536) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,\n"
			."\t `URL_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,\n"
			."\t `data` longtext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,\n"
			."\t `created_date_time` datetime NOT NULL,\n"
			."\t PRIMARY KEY (`URL_hash`)\n"
			."\t) ENGINE={$engine} DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
	}

	/**
	 *
	 */
	public function helper_create() {
		$this->_db_write->query( $this->helper_getCreateCommand() );
	}
}