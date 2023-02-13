<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
abstract class DataModel_Backend extends BaseObject
{
	const TYPE_MYSQL = 'MySQL';
	const TYPE_PGSQL = 'PgSQL';
	const TYPE_SQLITE = 'SQLite';
	
	/**
	 * @var array[]
	 */
	protected static array $backend_types = [
		self::TYPE_MYSQL,
		self::TYPE_PGSQL,
		self::TYPE_SQLITE,
	];

	/**
	 * @var ?DataModel_Config
	 */
	protected static ?DataModel_Config $_main_config = null;

	/**
	 *
	 * @var DataModel_Backend[]
	 */
	protected static array $custom_backends = [];

	/**
	 * @var ?DataModel_Backend
	 */
	protected static ?DataModel_Backend $default_backend = null;


	/**
	 * @var ?DataModel
	 */
	protected ?DataModel $_transaction_starter = null;

	/**
	 * @var ?DataModel_Backend_Config
	 */
	protected ?DataModel_Backend_Config $config = null;
	
	/**
	 * @param string $type
	 * @param string $class_name
	 * @param string $config_class_name
	 */
	public static function addBackendType( string $type, string $class_name, string $config_class_name ): void
	{
		if(!in_array($type, static::$backend_types)) {
			static::$backend_types[] = $type;
		}
		
		Factory_DataModel::setBackendClassName($type, $class_name);
		Factory_DataModel::setBackendConfigClassName($type, $config_class_name);
	}
	
	
	/**
	 * @return array
	 */
	public static function getAllBackendTypes() : array
	{
		$list = [];
		foreach( static::$backend_types as $type) {
			$backend = Factory_DataModel::getBackendInstance( $type );
			$list[$type] = $backend->getTitle();
		}
		
		return $list;
	}
	
	/**
	 * @return array
	 */
	public static function getAvlBackendTypes() : array
	{
		$list = [];
		foreach( static::$backend_types as $type) {
			$backend = Factory_DataModel::getBackendInstance( $type );
			if($backend->isAvailable()) {
				$list[$type] = $backend->getTitle();
			}
		}
		
		return $list;
	}
	


	/**
	 *
	 * @return DataModel_Config
	 */
	public static function getMainConfig(): DataModel_Config
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
	public static function getDefaultBackendType(): string
	{
		return self::getMainConfig()->getBackendType();
	}

	/**
	 * @param DataModel_Definition_Model $definition
	 *
	 * @return DataModel_Backend
	 */
	public static function get( DataModel_Definition_Model $definition ): DataModel_Backend
	{
		if( static::$custom_backends ) {
			if( $definition instanceof DataModel_Definition_Model_Related ) {
				$definition = $definition->getMainModelDefinition();
			}

			$class_name = $definition->getClassName();
			if( isset( static::$custom_backends[$class_name] ) ) {
				return static::$custom_backends[$class_name];
			}
		}


		return static::getDefaultBackend();
	}

	/**
	 * @return DataModel_Backend
	 */
	public static function getDefaultBackend(): DataModel_Backend
	{
		if( !static::$default_backend ) {
			$backend_type = static::getDefaultBackendType();

			static::$default_backend = Factory_DataModel::getBackendInstance(
				$backend_type,
				static::getMainConfig()->getBackendConfig()
			);
		}

		return static::$default_backend;
	}

	/**
	 * @param DataModel_Backend $default_backend
	 */
	public static function setDefaultBackend( DataModel_Backend $default_backend ): void
	{
		static::$default_backend = $default_backend;
	}


	/**
	 *
	 * @param string $data_model_class_name
	 * @param DataModel_Backend $backend
	 */
	public static function setCustomBackend( string $data_model_class_name, DataModel_Backend $backend ): void
	{
		static::$custom_backends[$data_model_class_name] = $backend;
	}

	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Backend|null
	 */
	public static function getCustomBackend( string $data_model_class_name ): DataModel_Backend|null
	{
		if( !isset( static::$custom_backends[$data_model_class_name] ) ) {
			return null;
		}

		return static::$custom_backends[$data_model_class_name];
	}

	/**
	 * @param string $data_model_class_name
	 *
	 * @return DataModel_Backend
	 */
	public static function unsetCustomBackend( string $data_model_class_name ): DataModel_Backend
	{
		if( isset( static::$custom_backends[$data_model_class_name] ) ) {
			unset( static::$custom_backends[$data_model_class_name] );
		}

		return static::$custom_backends[$data_model_class_name];
	}


	/**
	 *
	 * @param ?DataModel_Backend_Config $config
	 */
	public function __construct( ?DataModel_Backend_Config $config=null )
	{
		if($config) {
			$this->config = $config;
		}
	}
	
	/**
	 * @return string
	 */
	abstract public function getType() : string;
	
	/**
	 * @return string
	 */
	abstract public function getTitle() : string;
	
	/**
	 * @return bool
	 */
	abstract public function isAvailable() : bool;
	
	/**
	 * @return Db_Backend_Config|null
	 */
	abstract public function prepareDefaultDbConnectionConfig() : ?Db_Backend_Config;

	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	abstract public function createSelectQuery( DataModel_Query $query ): string;


	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	abstract public function createCountQuery( DataModel_Query $query ): string;

	/**
	 * @param DataModel_RecordData $record
	 *
	 *
	 * @return string
	 */
	abstract public function createInsertQuery( DataModel_RecordData $record ): string;

	/**
	 * @param DataModel_RecordData $record
	 * @param DataModel_Query $where
	 *
	 * @return string
	 */
	abstract public function createUpdateQuery( DataModel_RecordData $record, DataModel_Query $where ): string;

	/**
	 * @param DataModel_Query $where
	 *
	 * @return string
	 */
	abstract public function createDeleteQuery( DataModel_Query $where ): string;

	/**
	 * @param DataModel_RecordData $record
	 *
	 * @return mixed
	 */
	abstract public function save( DataModel_RecordData $record ): mixed;

	/**
	 * @param DataModel_RecordData $record
	 * @param DataModel_Query $where
	 *
	 * @return int
	 */
	abstract public function update( DataModel_RecordData $record, DataModel_Query $where ): int;

	/**
	 * @param DataModel_Query $where
	 *
	 * @return int
	 */
	abstract public function delete( DataModel_Query $where ): int;

	/**
	 * @param DataModel_Query $query
	 *
	 * @return int
	 */
	abstract public function getCount( DataModel_Query $query ): int;

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	abstract public function fetchAll( DataModel_Query $query ): mixed;

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	abstract public function fetchAssoc( DataModel_Query $query ): mixed;

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	abstract public function fetchPairs( DataModel_Query $query ): mixed;

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	abstract public function fetchRow( DataModel_Query $query ): mixed;

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	abstract public function fetchOne( DataModel_Query $query ): mixed;

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	abstract public function fetchCol( DataModel_Query $query ): mixed;

	/**
	 *
	 */
	abstract public function transactionStart(): void;

	/**
	 * @return bool
	 */
	public function getTransactionStarted(): bool
	{
		return (bool)$this->_transaction_starter;
	}

	/**
	 * @return DataModel|null
	 */
	public function getTransactionStarter(): DataModel|null
	{
		return $this->_transaction_starter;
	}

	/**
	 * @param ?DataModel $transaction_starter
	 */
	public function setTransactionStarter( ?DataModel $transaction_starter ): void
	{
		$this->_transaction_starter = $transaction_starter;
	}

	/**
	 *
	 */
	abstract public function transactionCommit(): void;

	/**
	 *
	 */
	abstract public function transactionRollback(): void;


	/**
	 * @param DataModel_Query $query
	 * @param string $fetch_method
	 * @param array $data
	 *
	 * @return array
	 */
	protected function validateResultData( DataModel_Query $query, string $fetch_method, array $data ): array
	{
		$fetch_row = ($fetch_method == 'fetchRow');
		$fetch_pairs = ($fetch_method == 'fetchPairs');


		if( $fetch_row ) {
			$data = [$data];
		}

		if( $fetch_pairs ) {

			/**
			 * @var DataModel_Query_Select_Item $item
			 * @var DataModel_Definition_Property $key_property
			 */
			$key_property = null;
			$value_property = null;
			
			foreach( $query->getSelect() as $item ) {
				if(!$key_property) {
					$key_property = $item->getItem();
					continue;
				}
				if(!$value_property) {
					$value_property = $item->getItem();
					continue;
				}
				
				break;
			}

			if(
				$value_property &&
				($value_property instanceof DataModel_Definition_Property) &&
				($key_property instanceof DataModel_Definition_Property)
			) {
				$_data = [];
				foreach( $data as $i => $d ) {
					$key_property->checkValueType( $i );
					$value_property->checkValueType( $d );
					$_data[$i] = $d;
				}
				$data = $_data;
			}

		} else {
			foreach( $data as $i => $d ) {
				foreach( $query->getSelect() as $item ) {
					/**
					 * @var DataModel_Query_Select_Item $item
					 * @var DataModel_Definition_Property $property
					 */
					$property = $item->getItem();

					if( !($property instanceof DataModel_Definition_Property) ) {
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
	protected function unserialize( string $string ): mixed
	{
		if( !$string ) {
			return null;
		}

		return unserialize( $string );
	}

	/**
	 * @param mixed $data
	 *
	 * @return string
	 */
	protected function serialize( mixed $data ): string
	{
		return serialize( $data );
	}

	/**
	 * @param DataModel_Definition_Model $definition
	 *
	 * @return bool
	 * @throws Exception
	 */
	abstract public function helper_tableExists( DataModel_Definition_Model $definition ): bool;

	/**
	 * @param DataModel_Definition_Model $definition
	 * @param ?string $force_table_name (optional)
	 *
	 * @return string
	 */
	abstract public function helper_getCreateCommand( DataModel_Definition_Model $definition, ?string $force_table_name = null ): string;

	/**
	 * @param DataModel_Definition_Model $definition
	 */
	abstract public function helper_create( DataModel_Definition_Model $definition ): void;

	/**
	 * @param DataModel_Definition_Model $definition
	 *
	 * @return string
	 */
	abstract public function helper_getDropCommand( DataModel_Definition_Model $definition ): string;

	/**
	 * @param DataModel_Definition_Model $definition
	 */
	abstract public function helper_drop( DataModel_Definition_Model $definition ): void;

	/**
	 * @param DataModel_Definition_Model $definition
	 *
	 * @return array
	 */
	abstract public function helper_getUpdateCommand( DataModel_Definition_Model $definition ): array;

	/**
	 * @param DataModel_Definition_Model $definition
	 *
	 * @throws Exception
	 */
	abstract public function helper_update( DataModel_Definition_Model $definition ): void;

}