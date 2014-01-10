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
 * @subpackage DataModel_Cache
 */
namespace Jet;

class DataModel_Cache_Backend_Oracle extends DataModel_Cache_Backend_Abstract {

	/**
	 * @var DataModel_Cache_Backend_Oracle_Config
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
		$this->_db_read->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, true);
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
		$data = $this->_db_read->fetchOne("SELECT data FROM {$this->_table_name}
				WHERE
					class_name=:class_name AND
					model_name=:model_name AND
					object_ID=:object_ID",
			array(
				"class_name" => get_class($data_model),
				"model_name" => $data_model->getDataModelName(),
				"object_ID" => (string)$ID,

			)
		);
		if(!$data) {
			return false;
		}

		return $this->unserialize($data);
	}

	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 * @param mixed $data
	 */
	public function save(DataModel $data_model, $ID, $data) {
		$this->_db_write->execCommand("INSERT INTO {$this->_table_name} (
						class_name,
						model_name,
						object_ID,
						created_date_time,
						data
					) VALUES (
						:class_name,
						:model_name,
						:object_ID,
						sysdate,
						:data
					)",
				array(
					"class_name" => get_class($data_model),
					"model_name" => $data_model->getDataModelName(),
					"object_ID" => (string)$ID,
					"data" => $this->serialize($data),

				));
	}

	/**
	 * @param DataModel $data_model
	 * @param string $ID
	 * @param mixed $data
	 */
	public function update(DataModel $data_model, $ID, $data) {

		$this->_db_write->execCommand( "UPDATE {$this->_table_name} SET
						data=:data,
						created_date_time=sysdate
					WHERE
						class_name=:class_name AND
						model_name=:model_name AND
						object_ID=:object_ID
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
	public function delete(DataModel $data_model, $ID) {
		$this->_db_write->execCommand("DELETE FROM {$this->_table_name} WHERE class_name=:class_name AND model_name=:model_name AND object_ID=:object_ID",
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
			$this->_db_write->execCommand("TRUNCATE TABLE {$this->_table_name}");
		} else {
			$this->_db_write->execCommand("DELETE FROM {$this->_table_name} WHERE model_name=:model_name",
				array(
					"model_name" => $model_name,
				));
		}
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
			."\t class_name varchar(255) NOT NULL,\n"
			."\t model_name varchar(255) NOT NULL,\n"
			."\t object_ID varchar(255) NOT NULL,\n"
			."\t data CLOB,\n"
			."\t created_date_time TIMESTAMP WITH TIME ZONE,\n"
			."\t CONSTRAINT {$this->_table_name}_pk PRIMARY KEY (class_name,model_name,object_ID)\n"
			."\t)';"
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