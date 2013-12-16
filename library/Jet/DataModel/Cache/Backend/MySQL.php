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

class DataModel_Cache_Backend_MySQL extends DataModel_Cache_Backend_Abstract {

	/**
	 * @var DataModel_Cache_Backend_MySQL_Config
	 */
	protected $config;

	/**
	 *
	 * @var string
	 */
	protected $_table_name;

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
	protected $_class_name = "";

	/**
	 * @var string
	 */
	protected $_model_name = "";


	public function initialize() {
		$this->_db_read = Db::get($this->config->getConnectionRead());
		$this->_db_write = Db::get($this->config->getConnectionWrite());
		$this->_table_name = $this->config->getTableName();
		$this->_class_name = get_class($this->data_model);
		$this->_model_name = $this->data_model->getDataModelName();
	}

	/**
	 * @param string $ID
	 *
	 * @return bool|mixed
	 */
	public function get($ID) {
		$data = $this->_db_read->fetchOne("SELECT `data` FROM `{$this->_table_name}`
				WHERE
					`class_name`=:class_name AND
					`model_name`=:model_name AND
					`object_ID`=:object_ID",
				array(
					"class_name" => $this->_class_name,
					"model_name" => $this->_model_name,
					"object_ID" => (string)$ID,

				)
			);
		if(!$data) {
			return false;
		}

		return unserialize($data);
	}

	/**
	 * @param string $ID
	 * @param mixed $data
	 */
	public function save($ID, $data) {

		$data = array(
			"class_name" => $this->_class_name,
			"model_name" => $this->_model_name,
			"object_ID" => (string)$ID,
			"data" => serialize($data)
		);

		$this->_db_write->query("INSERT IGNORE INTO `{$this->_table_name}` SET
					`class_name`=:class_name,
					`model_name`=:model_name,
					`object_ID`=:object_ID,
					`data`=:data,
					`created_date_time`=NOW()
				",$data);
	}

	/**
	 * @param string $ID
	 * @param mixed $data
	 */
	public function update($ID, $data) {

		$this->_db_write->query( "UPDATE `{$this->_table_name}` SET
						`data`=:data,
						`created_date_time`=NOW()
					WHERE
						`class_name`=:class_name AND
						`model_name`=:model_name AND
						`object_ID`=:object_ID
						",
			array(
				"data" => serialize($data),
				"class_name" => $this->_class_name,
				"model_name" => $this->_model_name,
				"object_ID" => (string)$ID
			) );
	}


	/**
	 * @param string $ID
	 */
	public function delete($ID) {
		$this->_db_write->query("DELETE FROM `{$this->_table_name}` WHERE `class_name`=:class_name AND `model_name`=:model_name AND `object_ID`=:object_ID",
			array(
				"class_name" => $this->_class_name,
				"model_name" => $this->_model_name,
				"object_ID" => (string)$ID,
			));
	}


	/**
	 * @param null|string $model_name (optional)
	 */
	public function truncate( $model_name=null ) {
		if(!$model_name) {
			$this->_db_write->query("TRUNCATE TABLE `{$this->_table_name}`");
		} else {
			$this->_db_write->query("DELETE FROM `{$this->_table_name}` WHERE `model_name`=:model_name",
				array(
					"model_name" => $this->_model_name,
				));
		}
	}

	/**
	 * @return string
	 */
	public function helper_getCreateCommand() {
		$engine = $this->config->getEngine();

		return "CREATE TABLE IF NOT EXISTS `{$this->_table_name}` (\n"
			."\t `class_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,\n"
			."\t `model_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,\n"
			."\t `object_ID` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,\n"
			."\t `data` longtext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,\n"
			."\t `created_date_time` datetime NOT NULL,\n"
			."\t PRIMARY KEY (`class_name`,`model_name`,`object_ID`)\n"
			."\t) ENGINE={$engine} DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
	}

	/**
	 *
	 */
	public function helper_create() {
		$this->_db_write->query( $this->helper_getCreateCommand() );
	}

}