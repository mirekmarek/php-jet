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
		$this->_current_operation_ID = DataModel_ID_Abstract::generateUniqueID();
		$this->_current_data_model = $data_model;

		$user = Auth::getCurrentUser();
		if($user) {
			$user_name = $user->getName()." (".$user->getLogin().")";
			$user_ID = (string)$user->getID();
		} else {
			$user_name = "";
			$user_ID = "";
		}

		$this->_db_write->execCommand("INSERT INTO {$this->_table_name}
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
											operation_inprogress
						                )
						                VALUES
						                (
						                    :operation_ID,
											:class_name,
											:model_name,
											:object_ID,
											:operation,
											trunc(sysdate),
											:user_name,
											:user_ID,
											:object,
											:operation_inprogress

						                )
				",array(
					"operation_ID" => $this->_current_operation_ID,
					"class_name" => get_class($this->_current_data_model),
					"model_name" => $this->_current_data_model->getDataModelName(),
					"object_ID" => (string)$this->_current_data_model->getID(),
					"operation" => $operation,
					"user_name" => $user_name,
					"user_ID" => $user_ID,
					"object" => $this->serialize( $this->_current_data_model ),
					"operation_inprogress" => 1,
				));

	}

	/**
	 *
	 */
	public function operationDone() {
		$this->_db_write->execCommand(
			"UPDATE {$this->_table_name} SET operation_inprogress=0, operation_done=1, done_date_and_time=trunc(sysdate) WHERE operation_ID=:operation_ID",
			array(
				"operation_ID" => $this->_current_operation_ID,
			)
		);
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
				."\toperation_ID varchar(100) NOT NULL,\n"
				."\tclass_name varchar(255) NOT NULL,\n"
				."\tmodel_name varchar(255) NOT NULL,\n"
				."\tobject_ID varchar(255) NOT NULL,\n"
				."\toperation varchar(50) NOT NULL,\n"
				."\tstart_date_and_time date NOT NULL,\n"
				."\tdone_date_and_time date NOT NULL,\n"
				."\toperation_inprogress char(4) NOT NULL,\n"
				."\toperation_done char(4) NOT NULL,\n"
				."\tuser_name varchar(255) NOT NULL,\n"
				."\tuser_ID varchar(255) NOT NULL,\n"
				."\tobject CLOB NOT NULL,\n"
				."\tCONSTRAINT {$this->_table_name}_pk PRIMARY KEY (operation_ID)\n"
			.")';"
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