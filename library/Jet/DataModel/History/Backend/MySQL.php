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
 * @subpackage DataModel_History
 */
namespace Jet;

class DataModel_History_Backend_MySQL extends DataModel_History_Backend_Abstract {
	/**
	 * @var DataModel_History_Backend_MySQL_Config
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


	/**
	 *
	 */
	public function initialize() {
		$this->_db_read = Db::get($this->config->getConnectionRead());
		$this->_db_write = Db::get($this->config->getConnectionWrite());
		$this->_table_name = $this->config->getTableName();
	}

	/**
	 * @param DataModel $data_model
	 * @param string $operation
	 */
	public function operationStart( DataModel $data_model, $operation ) {
		$this->_current_operation_ID = $this->generateOperationID();
		$this->_current_data_model = $data_model;

		$user = Auth::getCurrentUser();
		if($user) {
			$user_name = $user->getName().' ('.$user->getLogin().')';
			$user_ID = (string)$user->getID();
		} else {
			$user_name = '';
			$user_ID = '';
		}

		$this->_db_write->execCommand('INSERT INTO `'.$this->_table_name.'` SET
					`operation_ID`=:operation_ID,
					`class_name`=:class_name,
					`model_name`=:model_name,
					`object_ID`=:object_ID,
					`operation`=:operation,
					`start_date_and_time`=NOW(),
					`user_name`=:user_name,
					`user_ID`=:user_ID,
					`object`=:object,
					`operation_in_progress`=:operation_in_progress
				', [
					'operation_ID' => $this->_current_operation_ID,
					'class_name' => get_class($this->_current_data_model),
					'model_name' => $this->_current_data_model->getDataModelDefinition()->getModelName(),
					'object_ID' => (string)$this->_current_data_model->getID(),
					'operation' => $operation,
					'user_name' => $user_name,
					'user_ID' => $user_ID,
					'object' => serialize( $this->_current_data_model ),
					'operation_in_progress' => 1,
		]);

	}

	/**
	 *
	 */
	public function operationDone() {
		$this->_db_write->execCommand(
			'UPDATE `'.$this->_table_name.'` SET `operation_in_progress`=0, `operation_done`=1, `done_date_and_time`=NOW() WHERE `operation_ID`=:operation_ID',
			[
				'operation_ID' => $this->_current_operation_ID,
			]
		);
	}

	/**
	 * @return string
	 */
	public function helper_getCreateCommand() {
		$engine = $this->config->getEngine();

		return 'CREATE TABLE IF NOT EXISTS `'.$this->_table_name.'` ('.JET_EOL
				.JET_TAB.'`operation_ID` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,'.JET_EOL
				.JET_TAB.'`class_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,'.JET_EOL
				.JET_TAB.'`model_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,'.JET_EOL
				.JET_TAB.'`object_ID` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,'.JET_EOL
				.JET_TAB.'`operation` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,'.JET_EOL
				.JET_TAB.'`start_date_and_time` datetime NOT NULL,'.JET_EOL
				.JET_TAB.'`done_date_and_time` datetime,'.JET_EOL
				.JET_TAB.'`operation_in_progress` tinyint(4) NOT NULL,'.JET_EOL
				.JET_TAB.'`operation_done` tinyint(4) DEFAULT 0,'.JET_EOL
				.JET_TAB.'`user_name` varchar(255) CHARACTER SET utf8 NOT NULL,'.JET_EOL
				.JET_TAB.'`user_ID` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,'.JET_EOL
				.JET_TAB.'`object` longtext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,'.JET_EOL
				.JET_TAB.'PRIMARY KEY (`operation_ID`)'.JET_EOL
			.') ENGINE='.$engine.' DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
	}

	/**
	 *
	 */
	public function helper_create() {
		$this->_db_write->execCommand( $this->helper_getCreateCommand() );
	}


}