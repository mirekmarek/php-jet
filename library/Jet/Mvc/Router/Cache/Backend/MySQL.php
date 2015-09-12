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

class Mvc_Router_Cache_Backend_MySQL extends Mvc_Router_Cache_Backend_Abstract {

	/**
	 * @var Mvc_Router_Cache_Backend_MySQL_Config
	 */
	protected $config;

	/**
	 *
	 * @var Db_Connection_Abstract
	 */
	private $_db_read = null;
	/**
	 *
	 * @var Db_Connection_Abstract
	 */
	private $_db_write = null;

	/**
	 * @var string
	 */
	protected $_table_name = '';


	/**
	 * Initializes the cache backend
	 *
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
	 * @return  null|array
	 */
	public function load($URL) {
		$data = $this->_db_read->fetchOne('SELECT `data` FROM `'.$this->_table_name.'`
				WHERE
					`URL_hash`=:URL_hash',
			array(
				'URL_hash' => md5($URL)

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
	 * @param array $item
	 *
	 */
	public function save($URL, array $item) {

		$data = array(
			'URL' => $URL,
			'URL_hash' => md5($URL),
			'data' => serialize($item),
		);

		$this->_db_write->execCommand('INSERT IGNORE INTO `'.$this->_table_name.'` SET
					`URL`=:URL,
					`URL_hash`=:URL_hash,
					`data`=:data,
					`created_date_time`=NOW()
				',$data);

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
			$this->_db_write->execCommand('TRUNCATE TABLE `'.$this->_table_name.'`');
		} else {
			if(is_array($URL)) {
				foreach($URL as $_URL) {
					$this->_db_write->execCommand('DELETE FROM `'.$this->_table_name.'` WHERE `URL_hash`=:URL_hash',
						array(
							'URL_hash' => md5($_URL),
						));
				}
			} else {
				$this->_db_write->execCommand('DELETE FROM `'.$this->_table_name.'` WHERE `URL_hash`=:URL_hash',
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
		$engine = $this->config->getEngine();

		return 'CREATE TABLE IF NOT EXISTS `'.$this->_table_name.'` ('.JET_EOL
			.JET_TAB.' `URL` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,'.JET_EOL
			.JET_TAB.' `URL_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,'.JET_EOL
			.JET_TAB.' `data` longtext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,'.JET_EOL
			.JET_TAB.' `created_date_time` datetime NOT NULL,'.JET_EOL
			.JET_TAB.' PRIMARY KEY (`URL_hash`)'.JET_EOL
			.JET_TAB.') ENGINE='.$engine.' DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
	}

	/**
	 *
	 */
	public function helper_create() {
		$this->_db_write->execCommand( $this->helper_getCreateCommand() );
	}
}