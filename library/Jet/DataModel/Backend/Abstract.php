<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 * Class DataModel_Backend_Abstract
 * @package Jet
 */
abstract class DataModel_Backend_Abstract extends BaseObject {

	/**
	 * @var DataModel
	 */
	protected $_transaction_starter;

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
	}

	/**
	 * @return bool
	 */
	public function getTransactionStarted()
	{
		return (bool)$this->_transaction_starter;
	}

	/**
	 * @return DataModel
	 */
	public function getTransactionStarter()
	{
		return $this->_transaction_starter;
	}

	/**
	 * @param DataModel $transaction_starter
	 */
	public function setTransactionStarter($transaction_starter)
	{
		$this->_transaction_starter = $transaction_starter;
	}


	/**
	 *
	 */
	abstract public function initialize();

	/**
	 * @param DataModel_Definition_Model_Abstract $definition
	 * @param string|null $force_table_name (optional)
	 *
	 * @return string
	 */
	abstract public function helper_getCreateCommand( DataModel_Definition_Model_Abstract $definition, $force_table_name=null );

	/**
	 * @param DataModel_Definition_Model_Abstract $definition
	 */
	abstract public function helper_create( DataModel_Definition_Model_Abstract $definition );

	/**
	 * @param DataModel_Definition_Model_Abstract $definition
	 *
	 * @return string
	 */
	abstract public function helper_getDropCommand( DataModel_Definition_Model_Abstract $definition );

	/**
	 * @param DataModel_Definition_Model_Abstract $definition
	 */
	abstract public function helper_drop( DataModel_Definition_Model_Abstract $definition );

	/**
	 * @param DataModel_Definition_Model_Abstract $definition
	 *
	 * @return array
	 */
	abstract public function helper_getUpdateCommand( DataModel_Definition_Model_Abstract $definition );

	/**
	 * @param DataModel_Definition_Model_Abstract $definition
	 *
	 * @throws Exception
	 */
	abstract public function helper_update( DataModel_Definition_Model_Abstract $definition );


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
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	abstract public function fetchCol( DataModel_Query $query );

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


	/**
	 * @param DataModel_Query $query
	 * @param string $fetch_method
	 * @param array $data
	 * @return array
	 */
	protected function validateResultData( DataModel_Query $query, $fetch_method, $data ) {
		$fetch_row = ($fetch_method=='fetchRow');
		$fetch_pairs = ($fetch_method=='fetchPairs');


		if($fetch_row) {
			$data = [$data];
		}

		if($fetch_pairs) {
			foreach($query->getSelect() as $item) {
			}

			/**
			 * @var DataModel_Query_Select_Item $item
			 * @var DataModel_Definition_Property_Abstract $property
			 */
			$property = $item->getItem();

			foreach($data as $i=>$d) {
				$property->checkValueType( $d );
				$data[$i] = $d;
			}

		} else {
			foreach($data as $i=>$d) {
				foreach($query->getSelect() as $item) {
					/**
					 * @var DataModel_Query_Select_Item $item
					 * @var DataModel_Definition_Property_Abstract $property
					 */
					$property = $item->getItem();

					if( ! ($property instanceof DataModel_Definition_Property_Abstract) ) {
						continue;
					}

					$key = $item->getSelectAs();

					if($property->getMustBeSerializedBeforeStore()) {
						$data[$i][$key] = $this->unserialize( $data[$i][$key] );
					}

					$property->checkValueType( $data[$i][$key] );
				}
			}

		}

		if($fetch_row) {
			return $data[0];
		}

		return $data;

	}


	/**
	 * @param $data
	 *
	 * @return string
	 */
	protected function serialize( $data ) {
		return serialize($data);
	}

	/**
	 * @param $string
	 *
	 * @return mixed
	 */
	protected function unserialize( $string ) {
		return unserialize($string);
	}

}