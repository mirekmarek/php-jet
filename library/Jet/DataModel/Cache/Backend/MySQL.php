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
	 * @var Db_Connection_Abstract
	 */
	private $_db_read = null;
	/**
	 *
	 * @var Db_Connection_Abstract
	 */
	private $_db_write = null;


	public function initialize() {
		$this->_db_read = Db::get($this->config->getConnectionRead());
		$this->_db_write = Db::get($this->config->getConnectionWrite());
		$this->_table_name = $this->config->getTableName();
	}

	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 *
	 * @return bool|mixed
	 */
	public function get( DataModel $data_model, $ID) {
		$data = $this->_db_read->fetchOne('SELECT `data` FROM `'.$this->_table_name.'`
				WHERE
					`class_name`=:class_name AND
					`model_name`=:model_name AND
					`object_ID`=:object_ID',
			array(
				'class_name' => get_class($data_model),
				'model_name' => $data_model->getDataModelName(),
				'object_ID' => (string)$ID,

			)
		);
		if(!$data) {
			return false;
		}

		return unserialize($data);
	}

	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 * @param mixed $data
	 */
	public function save(DataModel $data_model, $ID, $data) {

		$data = array(
			'class_name' => get_class($data_model),
			'model_name' => $data_model->getDataModelName(),
			'object_ID' => (string)$ID,
			'data' => serialize($data)
		);

		$this->_db_write->execCommand('INSERT IGNORE INTO `'.$this->_table_name.'` SET
					`class_name`=:class_name,
					`model_name`=:model_name,
					`object_ID`=:object_ID,
					`data`=:data,
					`created_date_time`=NOW()
				',$data);
	}

	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 * @param mixed $data
	 */
	public function update(DataModel $data_model, $ID, $data) {

		$this->_db_write->execCommand( 'UPDATE `'.$this->_table_name.'` SET
						`data`=:data,
						`created_date_time`=NOW()
					WHERE
						`class_name`=:class_name AND
						`model_name`=:model_name AND
						`object_ID`=:object_ID
						',
			array(
				'data' => serialize($data),
				'class_name' => get_class($data_model),
				'model_name' => $data_model->getDataModelName(),
				'object_ID' => (string)$ID
			) );
	}


	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 */
	public function delete(DataModel $data_model, $ID) {
		$this->_db_write->execCommand('DELETE FROM `'.$this->_table_name.'` WHERE `class_name`=:class_name AND `model_name`=:model_name AND `object_ID`=:object_ID',
			array(
				'class_name' => get_class($data_model),
				'model_name' => $data_model->getDataModelName(),
				'object_ID' => (string)$ID,
			));
	}


	/**
	 * @param null|string $model_name (optional)
	 */
	public function truncate( $model_name=null ) {
		if(!$model_name) {
			$this->_db_write->execCommand('TRUNCATE TABLE `'.$this->_table_name.'`');
		} else {
			$this->_db_write->execCommand('DELETE FROM `'.$this->_table_name.'` WHERE `model_name`=:model_name',
				array(
					'model_name' => $model_name,
				));
		}
	}

	/**
	 * @return string
	 */
	public function helper_getCreateCommand() {
		$engine = $this->config->getEngine();

		return 'CREATE TABLE IF NOT EXISTS `'.$this->_table_name.'` ('.JET_EOL
			.JET_TAB.' `class_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,'.JET_EOL
			.JET_TAB.' `model_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,'.JET_EOL
			.JET_TAB.' `object_ID` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,'.JET_EOL
			.JET_TAB.' `data` longtext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,'.JET_EOL
			.JET_TAB.' `created_date_time` datetime NOT NULL,'.JET_EOL
			.JET_TAB.' PRIMARY KEY (`class_name`,`model_name`,`object_ID`)'.JET_EOL
			.JET_TAB.') ENGINE='.$engine.' DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
	}

	/**
	 *
	 */
	public function helper_create() {
		$this->_db_write->execCommand( $this->helper_getCreateCommand() );
	}

}