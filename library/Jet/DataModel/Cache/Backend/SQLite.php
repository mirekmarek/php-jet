<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Cache
 */
namespace Jet;

class DataModel_Cache_Backend_SQLite extends DataModel_Cache_Backend_Abstract {

	/**
	 * @var DataModel_Cache_Backend_SQLite_Config
	 */
	protected $config;

	/**
	 *
	 * @var string
	 */
	protected $_table_name;

	/**
	 *
	 * @var Db_Connection_Abstract
	 */
	private $_db = null;


	public function initialize() {
		$this->_db = Db::create('datamodel_sqlite_connection', [
			'name' => 'datamodel_cache_sqlite_connection',
			'driver' => Db::DRIVER_SQLITE,
			'DSN' => $this->config->getDSN()
		]);

		$this->_table_name = $this->config->getTableName();
	}

	/**
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 * @param string $ID
	 *
	 * @return bool|mixed
	 */
	public function get( DataModel_Definition_Model_Abstract $data_model_definition, $ID) {
		$data = $this->_db->fetchOne('SELECT `data` FROM `'.$this->_table_name.'`
				WHERE
					`class_name`=:class_name AND
					`model_name`=:model_name AND
					`object_ID`=:object_ID',
				[
					'class_name' => $data_model_definition->getClassName(),
					'model_name' => $data_model_definition->getModelName(),
					'object_ID' => (string)$ID,

				]
			);
		if(!$data) {
			return false;
		}

		$data = $this->unserialize($data);

		if(!$data) {
			$this->truncate();
			return false;

		}

		return $data;
	}

	/**
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 * @param string $ID
	 * @param mixed $data
	 */
	public function save(DataModel_Definition_Model_Abstract $data_model_definition, $ID, $data) {

		$data = [
			'class_name' => $data_model_definition->getClassName(),
			'model_name' => $data_model_definition->getModelName(),
			'object_ID' => (string)$ID,
			'data' => $this->serialize($data)
		];

		$this->_db->execCommand('INSERT OR IGNORE INTO `'.$this->_table_name.'`
					(
						`class_name`,
						`model_name`,
						`object_ID`,
						`data`,
						`created_date_time`
					) VALUES (
						:class_name,
						:model_name,
						:object_ID,
						:data,
						datetime(\'now\')
					)
				',$data);

	}

	/**
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 * @param string $ID
	 * @param mixed $data
	 */
	public function update(DataModel_Definition_Model_Abstract $data_model_definition, $ID, $data) {

		$this->_db->execCommand( 'UPDATE `'.$this->_table_name.'` SET
						`data`=:data,
						`created_date_time`=datetime(\'now\')
					WHERE
						`class_name`=:class_name AND
						`model_name`=:model_name AND
						`object_ID`=:object_ID
						',
			[
				'data' => $this->serialize($data),
				'class_name' => $data_model_definition->getClassName(),
				'model_name' => $data_model_definition->getModelName(),
				'object_ID' => (string)$ID
			]);
	}


	/**
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 * @param string $ID
	 */
	public function delete(DataModel_Definition_Model_Abstract $data_model_definition,$ID) {
		$this->_db->execCommand('DELETE FROM `'.$this->_table_name.'` WHERE `class_name`=:class_name AND `model_name`=:model_name AND `object_ID`=:object_ID',
			[
				'class_name' => $data_model_definition->getClassName(),
				'model_name' => $data_model_definition->getModelName(),
				'object_ID' => (string)$ID,
			]);
	}


	/**
	 * @param null|string $model_name (optional)
	 */
	public function truncate( $model_name=null ) {
		if(!$model_name) {
			$this->_db->execCommand('DELETE FROM `'.$this->_table_name.'`');
		} else {
			$this->_db->execCommand('DELETE FROM `'.$this->_table_name.'` WHERE `model_name`=:model_name',
				[
					'model_name' => $model_name,
				]);
		}
	}

	/**
	 * @return string
	 */
	public function helper_getCreateCommand() {

		return 'CREATE TABLE IF NOT EXISTS `'.$this->_table_name.'` ('.JET_EOL
			.JET_TAB.' `class_name` TEXT,'.JET_EOL
			.JET_TAB.' `model_name` TEXT,'.JET_EOL
			.JET_TAB.' `object_ID` TEXT,'.JET_EOL
			.JET_TAB.' `data` BLOB,'.JET_EOL
			.JET_TAB.' `created_date_time` NUMERIC,'.JET_EOL
			.JET_TAB.' PRIMARY KEY (`class_name`,`model_name`,`object_ID`)'.JET_EOL
			.JET_TAB.');';
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