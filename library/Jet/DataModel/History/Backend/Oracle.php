<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_History
 */
namespace Jet;

class DataModel_History_Backend_Oracle extends DataModel_History_Backend_Abstract {
	/**
	 * @var DataModel_History_Backend_Oracle_Config
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
		$this->_db_read->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, true);
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
			$user_name = 'none';
			$user_ID = 'none';
		}

		$this->_db_write->execCommand('INSERT INTO '.$this->_table_name.'
										(
											operation_ID,
											class_name,
											model_name,
											object_ID,
											operation,
											start_date_and_time,
											user_name,
											user_ID,
											object,
											operation_in_progress
										)
										VALUES
										(
											:operation_ID,
											:class_name,
											:model_name,
											:object_ID,
											:operation,
											sysdate,
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
		$this->_db_write->execCommand(
			'UPDATE '.$this->_table_name.' SET operation_in_progress=0, operation_done=1, done_date_and_time=sysdate WHERE operation_ID=:operation_ID',
			array(
				'operation_ID' => $this->_current_operation_ID,
			)
		);
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
				.JET_TAB.'operation_ID varchar(100) NOT NULL,'.JET_EOL
				.JET_TAB.'class_name varchar(255) NOT NULL,'.JET_EOL
				.JET_TAB.'model_name varchar(255) NOT NULL,'.JET_EOL
				.JET_TAB.'object_ID varchar(255) NOT NULL,'.JET_EOL
				.JET_TAB.'operation varchar(50) NOT NULL,'.JET_EOL
				.JET_TAB.'start_date_and_time TIMESTAMP WITH TIME ZONE NOT NULL,'.JET_EOL
				.JET_TAB.'done_date_and_time TIMESTAMP WITH TIME ZONE,'.JET_EOL
				.JET_TAB.'operation_in_progress char(4) NOT NULL,'.JET_EOL
				.JET_TAB.'operation_done char(4),'.JET_EOL
				.JET_TAB.'user_name varchar(255) NOT NULL,'.JET_EOL
				.JET_TAB.'user_ID varchar(255) NOT NULL,'.JET_EOL
				.JET_TAB.'object CLOB NOT NULL,'.JET_EOL
				.JET_TAB.'CONSTRAINT '.$this->_table_name.'_pk PRIMARY KEY (operation_ID)'.JET_EOL
			.')\';'
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