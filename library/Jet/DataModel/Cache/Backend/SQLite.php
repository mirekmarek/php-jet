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
		$this->_db = Db::create("datamodel_sqlite_connection", array(
			"name" => "datamodel_cache_sqlite_connection",
			"driver" => DB::DRIVER_SQLITE,
			"DSN" => $this->config->getDSN()
		));

		$this->_table_name = $this->config->getTableName();
	}

	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 *
	 * @return bool|mixed
	 */
	public function get( DataModel $data_model, $ID) {
		$data = $this->_db->fetchOne("SELECT `data` FROM `{$this->_table_name}`
				WHERE
					`class_name`=:class_name AND
					`model_name`=:model_name AND
					`object_ID`=:object_ID",
				array(
					"class_name" => get_class($data_model),
					"model_name" => $data_model->getDataModelName(),
					"object_ID" => (string)$ID,

				)
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
	 * @param DataModel $data_model
	 * @param string $ID
	 * @param mixed $data
	 */
	public function save(DataModel $data_model, $ID, $data) {

		$data = array(
			"class_name" => get_class($data_model),
			"model_name" => $data_model->getDataModelName(),
			"object_ID" => (string)$ID,
			"data" => $this->serialize($data)
		);

		$this->_db->execCommand("INSERT OR IGNORE INTO `{$this->_table_name}`
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
						date('now')
					)
				",$data);

	}

	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 * @param mixed $data
	 */
	public function update(DataModel $data_model, $ID, $data) {

		$this->_db->execCommand( "UPDATE `{$this->_table_name}` SET
						`data`=:data,
						`created_date_time`=NOW()
					WHERE
						`class_name`=:class_name AND
						`model_name`=:model_name AND
						`object_ID`=:object_ID
						",
			array(
				"data" => $this->serialize($data),
				"class_name" => get_class($data_model),
				"model_name" => $data_model->getDataModelName(),
				"object_ID" => (string)$ID
			) );
	}


	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 */
	public function delete(DataModel $data_model,$ID) {
		$this->_db->execCommand("DELETE FROM `{$this->_table_name}` WHERE `class_name`=:class_name AND `model_name`=:model_name AND `object_ID`=:object_ID",
			array(
				"class_name" => get_class($data_model),
				"model_name" => $data_model->getDataModelName(),
				"object_ID" => (string)$ID,
			));
	}


	/**
	 * @param null|string $model_name (optional)
	 */
	public function truncate( $model_name=null ) {
		if(!$model_name) {
			$this->_db->execCommand("DELETE FROM `{$this->_table_name}`");
		} else {
			$this->_db->execCommand("DELETE FROM `{$this->_table_name}` WHERE `model_name`=:model_name",
				array(
					"model_name" => $model_name,
				));
		}
	}

	/**
	 * @return string
	 */
	public function helper_getCreateCommand() {

		return "CREATE TABLE IF NOT EXISTS `{$this->_table_name}` (\n"
			."\t `class_name` TEXT,\n"
			."\t `model_name` TEXT,\n"
			."\t `object_ID` TEXT,\n"
			."\t `data` BLOB,\n"
			."\t `created_date_time` NUMERIC,\n"
			."\t PRIMARY KEY (`class_name`,`model_name`,`object_ID`)\n"
			."\t);";
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