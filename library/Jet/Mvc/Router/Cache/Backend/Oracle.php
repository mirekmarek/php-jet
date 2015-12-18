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
	protected $_table_name = '';


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
	 * @return  null|array
	 */
	public function load($URL) {

		$data = $this->_db_read->fetchOne('SELECT data FROM '.$this->_table_name.'
				WHERE
					URL_hash=:URL_hash',
			[
				'URL_hash' => md5($URL)
			]
		);
		if(!$data) {
			return null;
		}

		return $this->unserialize($data);
	}

	/**
	 *
	 * @param string $URL
	 * @param array $item
	 *
	 */
	public function save($URL, array $item) {
		$data = [
			'URL' => $URL,
			'URL_hash' => md5($URL),
			'data' => $this->serialize($item),
		];

		$this->_db_write->execCommand('
				BEGIN
					INSERT INTO '.$this->_table_name.'
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
						sysdate
					);
				EXCEPTION WHEN dup_val_on_index THEN
				      null;
				END;
				', $data);

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
			$this->_db_write->execCommand('TRUNCATE TABLE '.$this->_table_name);
		} else {
			if(is_array($URL)) {
				foreach($URL as $_URL) {
					$this->_db_write->execCommand('DELETE FROM '.$this->_table_name.' WHERE URL_hash=:URL_hash',
						[
							'URL_hash' => md5($_URL),
						]);
				}
			} else {
				$this->_db_write->execCommand('DELETE FROM '.$this->_table_name.' WHERE URL_hash=:URL_hash',
					[
						'URL_hash' => md5($URL),
					]);

			}
		}

	}

	/**
	 * @return string
	 */
	public function helper_getCreateCommand() {

		return
			'DECLARE'.JET_EOL
			.JET_TAB.'cnt NUMBER;'.JET_EOL
			.'BEGIN'.JET_EOL
			.JET_TAB.'SELECT count(*) INTO cnt FROM user_tables WHERE table_name = UPPER(\''.$this->_table_name.'\') or table_name = \''.$this->_table_name.'\';'.JET_EOL
			.'IF cnt = 0 THEN'.JET_EOL
			.'EXECUTE IMMEDIATE \'CREATE TABLE '.$this->_table_name.' ('.JET_EOL
			.JET_TAB.'URL varchar(3000) NOT NULL,'.JET_EOL
			.JET_TAB.'URL_hash varchar(255) NOT NULL,'.JET_EOL
			.JET_TAB.'data CLOB NOT NULL,'.JET_EOL
			.JET_TAB.'created_date_time TIMESTAMP WITH TIME ZONE NOT NULL,'.JET_EOL
			.JET_TAB.'CONSTRAINT '.$this->_table_name.'_pk PRIMARY KEY (URL_hash)'.JET_EOL
			.JET_TAB.')\';'
			.'END IF;'.JET_EOL
			.'END;'.JET_EOL;
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