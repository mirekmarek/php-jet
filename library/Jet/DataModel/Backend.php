<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
abstract class DataModel_Backend extends BaseObject
{
	/**
	 * @var array
	 */
	protected static $backend_types = [
			'MySQL' => [
				'title' => 'MySQL / MariaDB',
				'driver' => Db::DRIVER_MYSQL
			],
			'SQLite' => [
				'title' => 'SQLite',
				'driver' => Db::DRIVER_SQLITE
			]

		];

	/**
	 * @var DataModel_Config
	 */
	protected static $_main_config;

	/**
	 *
	 * @var DataModel_Backend[]
	 */
	protected static $custom_backends = [];

	/**
	 * @var DataModel_Backend
	 */
	protected static $default_backend;


	/**
	 * @var DataModel
	 */
	protected $_transaction_starter;

	/**
	 * @var DataModel_Backend_Config
	 */
	protected $config;

	/**
	 * @param bool $as_hash
	 *
	 * @return array
	 */
	public static function getBackendTypes( $as_hash=false )
	{

		$drivers = Db_Backend_PDO_Config::getDrivers();

		foreach( static::$backend_types as $type => $data ) {
			if( !in_array( $data['driver'], $drivers ) ) {
				unset( static::$backend_types[$type] );
			} else {
				static::$backend_types[$type]['type'] = $type;
			}
		}

		if( $as_hash ) {
			$types = [];

			foreach( static::$backend_types as $type => $d ) {
				$types[$type] = $d['title'];
			}

			return $types;

		}

		return static::$backend_types;

	}

	/**
	 * @param string $type
	 * @param string $driver
	 * @param string $title
	 */
	public static function addBackendType( $type, $driver, $title )
	{
		static::$backend_types[$type] = [
			'title'  => $title,
			'driver' => $driver
		];
	}


	/**
	 *
	 * @return DataModel_Config
	 */
	public static function getMainConfig()
	{
		if( !static::$_main_config ) {
			static::$_main_config = new DataModel_Config();
		}

		return static::$_main_config;
	}

	/**
	 *
	 * @return string
	 */
	public static function getDefaultBackendType()
	{
		return self::getMainConfig()->getBackendType();
	}

	/**
	 * @param DataModel_Definition_Model $definition
	 *
	 * @return DataModel_Backend
	 */
	public static function get( DataModel_Definition_Model $definition )
	{
		if(static::$custom_backends) {
			if($definition instanceof DataModel_Definition_Model_Related) {
				$definition = $definition->getMainModelDefinition();
			}

			$class_name = $definition->getClassName();
			if( isset(static::$custom_backends[$class_name]) ) {
				return static::$custom_backends[$class_name];
			}
		}


		return static::getDefaultBackend();
	}

	/**
	 * @return DataModel_Backend
	 */
	public static function getDefaultBackend()
	{
		if(!static::$default_backend) {
			$backend_type = static::getDefaultBackendType();

			static::$default_backend = DataModel_Factory::getBackendInstance(
				$backend_type,
				static::getMainConfig()->getBackendConfig()
			);
		}

		return static::$default_backend;
	}

	/**
	 * @param DataModel_Backend $default_backend
	 */
	public static function setDefaultBackend( DataModel_Backend $default_backend )
	{
		static::$default_backend = $default_backend;
	}



	/**
	 *
	 * @param string            $data_model_class_name
	 * @param DataModel_Backend $backend
	 */
	public static function setCustomBackend( $data_model_class_name, DataModel_Backend $backend )
	{
		static::$custom_backends[$data_model_class_name] = $backend;
	}

	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Backend|null
	 */
	public static function getCustomBackend( $data_model_class_name )
	{
		if(!isset(static::$custom_backends[$data_model_class_name])) {
			return null;
		}

		return static::$custom_backends[$data_model_class_name];
	}

	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Backend
	 */
	public static function unsetCustomBackend( $data_model_class_name )
	{
		if(isset(static::$custom_backends[$data_model_class_name])) {
			unset(static::$custom_backends[$data_model_class_name]);
		}

		return static::$custom_backends[$data_model_class_name];
	}


	/**
	 *
	 * @param DataModel_Backend_Config $config
	 */
	public function __construct( DataModel_Backend_Config $config )
	{
		$this->config = $config;
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	abstract public function createSelectQuery( DataModel_Query $query );


	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	abstract public function createCountQuery( DataModel_Query $query );

	/**
	 * @param DataModel_RecordData $record
	 *
	 *
	 * @return string
	 */
	abstract public function createInsertQuery( DataModel_RecordData $record );

	/**
	 * @param DataModel_RecordData $record
	 * @param DataModel_Query      $where
	 *
	 * @return string
	 */
	abstract public function createUpdateQuery( DataModel_RecordData $record, DataModel_Query $where );

	/**
	 * @param DataModel_Query $where
	 *
	 * @return string
	 */
	abstract public function createDeleteQuery( DataModel_Query $where );

	/**
	 * @param DataModel_RecordData $record
	 *
	 * @return mixed
	 */
	abstract public function save( DataModel_RecordData $record );

	/**
	 * @param DataModel_RecordData $record
	 * @param DataModel_Query      $where
	 *
	 * @return int
	 */
	abstract public function update( DataModel_RecordData $record, DataModel_Query $where );

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
	public function setTransactionStarter( $transaction_starter )
	{
		$this->_transaction_starter = $transaction_starter;
	}

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
	 * @param string          $fetch_method
	 * @param array           $data
	 *
	 * @return array
	 */
	protected function validateResultData( DataModel_Query $query, $fetch_method, $data )
	{
		$fetch_row = ( $fetch_method=='fetchRow' );
		$fetch_pairs = ( $fetch_method=='fetchPairs' );


		if( $fetch_row ) {
			$data = [ $data ];
		}

		if( $fetch_pairs ) {

			/**
			 * @var DataModel_Query_Select_Item   $item
			 * @var DataModel_Definition_Property $property
			 */
			$property = $item->getItem();

			foreach( $data as $i => $d ) {
				$property->checkValueType( $d );
				$data[$i] = $d;
			}

		} else {
			foreach( $data as $i => $d ) {
				foreach( $query->getSelect() as $item ) {
					/**
					 * @var DataModel_Query_Select_Item   $item
					 * @var DataModel_Definition_Property $property
					 */
					$property = $item->getItem();

					if( !( $property instanceof DataModel_Definition_Property ) ) {
						continue;
					}

					$key = $item->getSelectAs();

					if( $property->getMustBeSerializedBeforeStore() ) {
						$data[$i][$key] = $this->unserialize( $data[$i][$key] );
					}

					$property->checkValueType( $data[$i][$key] );
				}
			}

		}

		if( $fetch_row ) {
			return $data[0];
		}

		return $data;

	}

	/**
	 * @param string $string
	 *
	 * @return mixed
	 */
	protected function unserialize( $string )
	{
		if(!$string) {
			return null;
		}

		return unserialize( $string );
	}

	/**
	 * @param mixed $data
	 *
	 * @return string
	 */
	protected function serialize( $data )
	{
		return serialize( $data );
	}

	/**
	 * @param DataModel_Definition_Model $definition
	 *
	 * @throws Exception
	 */
	abstract public function helper_tableExists( DataModel_Definition_Model $definition );

	/**
	 * @param DataModel_Definition_Model $definition
	 * @param string|null                $force_table_name (optional)
	 *
	 * @return string
	 */
	abstract public function helper_getCreateCommand( DataModel_Definition_Model $definition, $force_table_name = null );

	/**
	 * @param DataModel_Definition_Model $definition
	 */
	abstract public function helper_create( DataModel_Definition_Model $definition );

	/**
	 * @param DataModel_Definition_Model $definition
	 *
	 * @return string
	 */
	abstract public function helper_getDropCommand( DataModel_Definition_Model $definition );

	/**
	 * @param DataModel_Definition_Model $definition
	 */
	abstract public function helper_drop( DataModel_Definition_Model $definition );

	/**
	 * @param DataModel_Definition_Model $definition
	 *
	 * @return array
	 */
	abstract public function helper_getUpdateCommand( DataModel_Definition_Model $definition );

	/**
	 * @param DataModel_Definition_Model $definition
	 *
	 * @throws Exception
	 */
	abstract public function helper_update( DataModel_Definition_Model $definition );

}