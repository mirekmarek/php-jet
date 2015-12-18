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
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 * @param string $ID
	 *
	 * @return bool|mixed
	 */
	public function get( DataModel_Definition_Model_Abstract $data_model_definition, $ID) {
		$data = $this->_db_read->fetchOne('SELECT data FROM '.$this->_table_name.'
				WHERE
					class_name=:class_name AND
					model_name=:model_name AND
					object_ID=:object_ID',
			[
				'class_name' => $data_model_definition->getClassName(),
				'model_name' => $data_model_definition->getModelName(),
				'object_ID' => (string)$ID,

			]
		);
		if(!$data) {
			return false;
		}

		return $this->unserialize($data);
	}

	/**
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 * @param string $ID
	 * @param mixed $data
	 */
	public function save(DataModel_Definition_Model_Abstract $data_model_definition, $ID, $data) {
		$this->_db_write->execCommand('
				BEGIN
					INSERT INTO '.$this->_table_name.' (
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
					);
				EXCEPTION WHEN dup_val_on_index THEN
					  null;
				END;
				',
				[
					'class_name' => $data_model_definition->getClassName(),
					'model_name' => $data_model_definition->getModelName(),
					'object_ID' => (string)$ID,
					'data' => $this->serialize($data),

				]);
	}

	/**
	 * @param DataModel_Definition_Model_Abstract $data_model_definition
	 * @param string $ID
	 * @param mixed $data
	 */
	public function update(DataModel_Definition_Model_Abstract $data_model_definition, $ID, $data) {

		$this->_db_write->execCommand( 'UPDATE '.$this->_table_name.' SET
						data=:data,
						created_date_time=sysdate
					WHERE
						class_name=:class_name AND
						model_name=:model_name AND
						object_ID=:object_ID
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
	public function delete(DataModel_Definition_Model_Abstract $data_model_definition, $ID) {
		$this->_db_write->execCommand('DELETE FROM '.$this->_table_name.' WHERE class_name=:class_name AND model_name=:model_name AND object_ID=:object_ID',
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
			$this->_db_write->execCommand('TRUNCATE TABLE '.$this->_table_name);
		} else {
			$this->_db_write->execCommand('DELETE FROM '.$this->_table_name.' WHERE model_name=:model_name',
				[
					'model_name' => $model_name,
				]);
		}
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
			.JET_TAB.' class_name varchar(255) NOT NULL,'.JET_EOL
			.JET_TAB.' model_name varchar(255) NOT NULL,'.JET_EOL
			.JET_TAB.' object_ID varchar(255) NOT NULL,'.JET_EOL
			.JET_TAB.' data CLOB,'.JET_EOL
			.JET_TAB.' created_date_time TIMESTAMP WITH TIME ZONE,'.JET_EOL
			.JET_TAB.' CONSTRAINT '.$this->_table_name.'_pk PRIMARY KEY (class_name,model_name,object_ID)'.JET_EOL
			.JET_TAB.')\';'
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