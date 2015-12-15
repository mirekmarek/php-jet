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

class DataModel_History_Backend_SQLite extends DataModel_History_Backend_Abstract {
	/**
	 * @var DataModel_History_Backend_SQLite_Config
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


	/**
	 *
	 */
	public function initialize() {
		$this->_db = Db::create('datamodel_history_sqlite_connection', array(
			'name' => 'datamodel_history_sqlite_connection',
			'driver' => DB::DRIVER_SQLITE,
			'DSN' => $this->config->getDSN()
		));

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

		$this->_db->execCommand('INSERT INTO `'.$this->_table_name.'`
					(
						`operation_ID`,
						`class_name`,
						`model_name`,
						`object_ID`,
						`operation`,
						`start_date_and_time`,
						`user_name`,
						`user_ID`,
						`object`,
						`operation_in_progress`

					) VALUES (
						:operation_ID,
						:class_name,
						:model_name,
						:object_ID,
						:operation,
						datetime(\'now\'),
						:user_name,
						:user_ID,
						:object,
						:operation_in_progress

					)
				',array(
					'operation_ID' => $this->_current_operation_ID,
					'class_name' => get_class($this->_current_data_model),
					'model_name' => $this->_current_data_model->getDataModelDefinition()->getModelName(),
					'object_ID' => (string)$this->_current_data_model->getID(),
					'operation' => $operation,
					'user_name' => $user_name,
					'user_ID' => $user_ID,
					'object' => $this->serialize( $this->_current_data_model ),
					'operation_in_progress' => 1,
				));

	}

	/**
	 *
	 */
	public function operationDone() {
		$this->_db->execCommand(
			'UPDATE `'.$this->_table_name.'` SET `operation_in_progress`=0, `operation_done`=1, `done_date_and_time`=datetime(\'now\') WHERE `operation_ID`=:operation_ID',
			array(
				'operation_ID' => $this->_current_operation_ID,
			)
		);
	}

	/**
	 *
	 * @return string
	 */
	public function helper_getCreateCommand() {

		return 'CREATE TABLE IF NOT EXISTS `'.$this->_table_name.'` ('.JET_EOL
				.JET_TAB.'`operation_ID` TEXT,'.JET_EOL
				.JET_TAB.'`class_name` TEXT,'.JET_EOL
				.JET_TAB.'`model_name` TEXT,'.JET_EOL
				.JET_TAB.'`object_ID` TEXT,'.JET_EOL
				.JET_TAB.'`operation` TEXT,'.JET_EOL
				.JET_TAB.'`start_date_and_time` NUMERIC,'.JET_EOL
				.JET_TAB.'`done_date_and_time` NUMERIC,'.JET_EOL
				.JET_TAB.'`operation_in_progress` INTEGER,'.JET_EOL
				.JET_TAB.'`operation_done` INTEGER,'.JET_EOL
				.JET_TAB.'`user_name` TEXT,'.JET_EOL
				.JET_TAB.'`user_ID` TEXT,'.JET_EOL
				.JET_TAB.'`object` BLOB,'.JET_EOL
				.JET_TAB.'PRIMARY KEY (`operation_ID`)'.JET_EOL
			.');';
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