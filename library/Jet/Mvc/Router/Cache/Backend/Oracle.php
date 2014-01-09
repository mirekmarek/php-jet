<?php
/**
 *
 *
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

class Mvc_Router_Cache_Backend_Oracle extends Mvc_Router_Cache_Backend_Abstract {

	/**
	 * @var Mvc_Router_Cache_Backend_Oracle_Config
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
	protected $_table_name = "";


	/**
	 * Initializes the cache backend
	 *
	 */
	public function initialize() {
		$this->_db_read = Db::get($this->config->getConnectionRead());
		$this->_db_read->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, true);
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

		$data = $this->_db_read->fetchOne("SELECT data FROM {$this->_table_name}
				WHERE
					URL_hash=:URL_hash",
			array(
				"URL_hash" => md5($URL)
			)
		);
		if(!$data) {
			return null;
		}

		return $this->unserialize($data);
	}

	/**
	 *
	 * @param string $URL
	 * @param Mvc_Router_Abstract $item
	 *
	 */
	public function save($URL, Mvc_Router_Abstract $item) {
		$data = array(
			"URL" => $URL,
			"URL_hash" => md5($URL),
			"data" => $this->serialize($item),
		);

		//TODO: IGNORE ...
		$this->_db_write->execCommand("INSERT INTO {$this->_table_name}
					(
						URL,
						URL_hash,
						data,
						created_date_time
					)
					VALUES
					(
						:URL,
						:URL_hash,
						:data,
						trunc(sysdate)
					)
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
	 *
	 */
	public function truncate($URL = null) {
		if($URL===null) {
			$this->_db_write->execCommand("TRUNCATE TABLE {$this->_table_name}");
		} else {
			if(is_array($URL)) {
				foreach($URL as $_URL) {
					$this->_db_write->execCommand("DELETE FROM {$this->_table_name} WHERE URL_hash=:URL_hash",
						array(
							"URL_hash" => md5($_URL),
						));
				}
			} else {
				$this->_db_write->execCommand("DELETE FROM {$this->_table_name} WHERE URL_hash=:URL_hash",
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

		return
			""
			."DECLARE\n"
			."\tcnt NUMBER;\n"
			."BEGIN\n"
			."\tSELECT count(*) INTO cnt FROM user_tables WHERE table_name = UPPER('{$this->_table_name}') or table_name = '{$this->_table_name}';\n"
			."IF cnt = 0 THEN\n"
			."EXECUTE IMMEDIATE 'CREATE TABLE {$this->_table_name} (\n"
			."\t URL varchar(3000) NOT NULL,\n"
			."\t URL_hash varchar(255) NOT NULL,\n"
			."\t data CLOB NOT NULL,\n"
			."\t created_date_time date NOT NULL,\n"
			."\tCONSTRAINT {$this->_table_name}_pk PRIMARY KEY (URL_hash)\n"
			."\t)';"
			."END IF;\n"
			."END;\n";
	}

	/**
	 *
	 */
	public function helper_create() {
		$this->_db_write->execCommand( $this->helper_getCreateCommand() );
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