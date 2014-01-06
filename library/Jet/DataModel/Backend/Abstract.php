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
 * @subpackage DataModel_Backend
 */
namespace Jet;

abstract class DataModel_Backend_Abstract extends Object {

	/**
	 * @var null
	 */
	protected static $__factory_class_name = null;
	/**
	 * @var null
	 */
	protected static $__factory_method_name = null;
	/**
	 * @var string
	 */
	protected static $__factory_must_be_instance_of_class_name = "Jet\\DataModel_Backend_Abstract";

	/**
	 * @var DataModel_Backend_Config_Abstract
	 */
	protected $config;

	/**
	 *
	 * @param DataModel_Backend_Config_Abstract $config
	 */
	public function  __construct( DataModel_Backend_Config_Abstract $config ) {
		$this->config = $config;
		$this->initialize();
	}

	/**
	 * Initializes ,,,
	 *
	 */
	abstract public function initialize();

	/**
	 * @param DataModel $data_model
	 * @param string|null $force_table_name (optional)
	 *
	 * @return string
	 */
	abstract public function helper_getCreateCommand( DataModel $data_model, $force_table_name=null );

	/**
	 * @param DataModel $data_model
	 */
	abstract public function helper_create( DataModel $data_model );

	/**
	 * @param DataModel $data_model
	 *
	 * @return string
	 */
	abstract public function helper_getDropCommand( DataModel $data_model );

	/**
	 * @param DataModel $data_model
	 */
	abstract public function helper_drop( DataModel $data_model );

	/**
	 * @param DataModel $data_model
	 *
	 * @return array
	 */
	abstract public function helper_getUpdateCommand( DataModel $data_model );

	/**
	 * @param DataModel $data_model
	 *
	 * @throws Exception
	 */
	abstract public function helper_update( DataModel $data_model );


	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	abstract public function getBackendSelectQuery( DataModel_Query $query );


	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	abstract public function getBackendCountQuery( DataModel_Query $query );

	/**
	 * @param DataModel_RecordData $record
	 *
	 *
	 * @return string
	 */
	abstract public function getBackendInsertQuery( DataModel_RecordData $record );

	/**
	 * @param DataModel_RecordData $record
	 * @param DataModel_Query $where
	 *
	 * @return string
	 */
	abstract function getBackendUpdateQuery( DataModel_RecordData $record, DataModel_Query $where );

	/**
	 * @param DataModel_Query $where
	 *
	 * @return string
	 */
	abstract public function getBackendDeleteQuery( DataModel_Query $where );

	/**
	 * @param DataModel_RecordData $record
	 *
	 * @return mixed
	 */
	abstract public function save( DataModel_RecordData $record );

	/**
	 * @param DataModel_RecordData $record
	 * @param DataModel_Query $where
	 *
	 * @return int
	 */
	abstract public function update( DataModel_RecordData $record, DataModel_Query $where);

	/**
	 * @param DataModel_Query $where
	 *
	 * @return int
	 */
	abstract public function delete( DataModel_Query $where );

	/**
	 * @param DataModel_Query $query
	 *
	 * @return int
	 */
	abstract public function getCount( DataModel_Query $query );

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	abstract public function fetchAll( DataModel_Query $query );

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	abstract public function fetchAssoc( DataModel_Query $query );

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	abstract public function fetchPairs( DataModel_Query $query );

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	abstract public function fetchRow( DataModel_Query $query );

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	abstract public function fetchOne( DataModel_Query $query );


	/**
	 *
	 */
	abstract public function transactionStart();

	/**
	 *
	 */
	abstract public function transactionCommit();

	/**
	 *
	 */
	abstract public function transactionRollback();

}