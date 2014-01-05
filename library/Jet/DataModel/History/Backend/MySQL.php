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
	 * @param string $operation
	 */
	public function operationStart( $operation ) {
		$this->_current_operation_ID = DataModel_ID_Abstract::generateUniqueID();

		$user = Auth::getCurrentUser();
		if($user) {
			$user_name = $user->getName()." (".$user->getLogin().")";
			$user_ID = (string)$user->getID();
		} else {
			$user_name = "";
			$user_ID = "";
		}

		$this->_db_write->execCommand("INSERT INTO `{$this->_table_name}` SET
 					`operation_ID`=:operation_ID,
					`class_name`=:class_name,
					`model_name`=:model_name,
					`object_ID`=:object_ID,
					`operation`=:operation,
					`start_date_and_time`=NOW(),
					`user_name`=:user_name,
					`user_ID`=:user_ID,
					`object`=:object,
					`operation_inprogress`=:operation_inprogress
				",array(
			"operation_ID" => $this->_current_operation_ID,
			"class_name" => get_class($this->data_model),
			"model_name" => $this->data_model->getDataModelName(),
			"object_ID" => (string)$this->data_model->getID(),
			"operation" => $operation,
			"user_name" => $user_name,
			"user_ID" => $user_ID,
			"object" => serialize( $this->data_model ),
			"operation_inprogress" => 1,
		));

	}

	/**
	 *
	 */
	public function operationDone() {
		$this->_db_write->execCommand(
			"UPDATE `{$this->_table_name}` SET `operation_inprogress`=0, `operation_done`=1, `done_date_and_time`=NOW() WHERE `operation_ID`=:operation_ID",
			array(
				"operation_ID" => $this->_current_operation_ID,
			)
		);
	}

	/**
	 * @return string
	 */
	public function helper_getCreateCommand() {
		$engine = $this->config->getEngine();

		return "CREATE TABLE IF NOT EXISTS `{$this->_table_name}` (\n"
				."\t`operation_ID` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,\n"
				."\t`class_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,\n"
				."\t`model_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,\n"
				."\t`object_ID` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,\n"
				."\t`operation` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,\n"
				."\t`start_date_and_time` datetime NOT NULL,\n"
				."\t`done_date_and_time` datetime NOT NULL,\n"
				."\t`operation_inprogress` tinyint(4) NOT NULL,\n"
				."\t`operation_done` tinyint(4) NOT NULL,\n"
				."\t`user_name` varchar(255) CHARACTER SET utf8 NOT NULL,\n"
				."\t`user_ID` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,\n"
				."\t`object` longtext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,\n"
				."\tPRIMARY KEY (`operation_ID`)\n"
			.") ENGINE={$engine} DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
	}

	/**
	 *
	 */
	public function helper_create() {
		$this->_db_write->execCommand( $this->helper_getCreateCommand() );
	}

	
}