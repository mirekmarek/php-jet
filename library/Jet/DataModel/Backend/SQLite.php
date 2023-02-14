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
class DataModel_Backend_SQLite extends DataModel_Backend
{
	use DataModel_Backend_Trait_Fetch;
	
	const PRIMARY_KEY_NAME = 'PRIMARY';
	/**
	 * @var array
	 */
	protected static array $valid_key_types = [
		DataModel::KEY_TYPE_PRIMARY,
		DataModel::KEY_TYPE_INDEX,
		DataModel::KEY_TYPE_UNIQUE,
	];
	/**
	 * @var ?DataModel_Backend_SQLite_Config
	 */
	protected ?DataModel_Backend_Config $config = null;
	/**
	 *
	 * @var ?Db_Backend_Interface
	 */
	private ?Db_Backend_Interface $_db = null;
	
	
	
	/**
	 * @return string
	 */
	public function getType() : string
	{
		return DataModel_Backend::TYPE_SQLITE;
	}
	
	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return 'SQLite';
	}
	
	/**
	 * @return bool
	 */
	public function isAvailable(): bool
	{
		return in_array(
			Db::DRIVER_SQLITE,
			Db_Backend_PDO_Config::getDrivers()
		);
	}
	
	
	/**
	 * @return Db_Backend_Config|null
	 */
	public function prepareDefaultDbConnectionConfig() : ?Db_Backend_Config
	{
		$db_connection_config = new Db_Backend_PDO_Config();
		$db_connection_config->setDriver( Db::DRIVER_SQLITE );
		$db_connection_config->setName('default');
		$db_connection_config->initDefault();
		
		return $db_connection_config;
	}


	/**
	 * @return Db_Backend_Interface
	 */
	public function getDb(): Db_Backend_Interface
	{
		if( !$this->_db ) {
			$this->_db = Db::get( $this->config->getConnection() );
		}

		return $this->_db;
	}

	/**
	 * @param Db_Backend_Interface $db
	 */
	public function setDb( Db_Backend_Interface $db ): void
	{
		$this->_db = $db;
	}


	/**
	 *
	 * @param DataModel_Definition_Model $definition
	 *
	 * @return bool
	 */
	public function helper_tableExists( DataModel_Definition_Model $definition ): bool
	{
		$table_name = $this->_getTableName( $definition, false );

		$db = $this->getDb();

		if( $db->fetchOne( "SELECT name FROM sqlite_master WHERE type='table' AND name='$table_name';" ) ) {
			return true;
		}

		return false;
	}


	/**
	 * @param DataModel_Definition_Model $definition
	 */
	public function helper_create( DataModel_Definition_Model $definition ): void
	{
		$this->getDb()->execute( $this->helper_getCreateCommand( $definition ) );
	}

	/**
	 * @param DataModel_Definition_Model $definition
	 * @param string|null $force_table_name (optional)
	 *
	 * @return string
	 */
	public function helper_getCreateCommand( DataModel_Definition_Model $definition, ?string $force_table_name = null ): string
	{

		/*
		$options = [];

		$_options = [];

		foreach( $options as $o => $v ) {
			$_options[] = $o . '=' . $v;
		}

		$_options = implode( ' ', $_options );
		*/
		$_options = '';

		$_columns = [];

		foreach( $definition->getProperties() as $property ) {
			if( !$property->getCanBeTableField() ) {
				continue;
			}

			$_columns[] = "\t" . $this->_getColumnName( $property, true, false ) . ' ' . $this->_getSQLType( $property );
		}

		$table_name = $force_table_name ? : $this->_getTableName( $definition );

		$create_index_query = [];
		$keys = [];

		$has_ai = false;
		foreach( $definition->getProperties() as $property ) {
			if( $property->getType() == DataModel::TYPE_ID_AUTOINCREMENT ) {
				$has_ai = true;
				break;
			}
		}

		foreach( $definition->getKeys() as $key_name => $key ) {
			$key_columns = [];
			foreach( $key->getPropertyNames() as $property_name ) {
				$property = $definition->getProperty( $property_name );
				$key_columns[] = $this->_getColumnName( $property, true, false );
			}

			$key_columns = implode( ', ', $key_columns );


			switch( $key->getType() ) {
				case DataModel::KEY_TYPE_PRIMARY:
					if( !$has_ai ) {
						$keys[] = PHP_EOL . "\t" . ',PRIMARY KEY (' . $key_columns . ')';
					}
					break;
				case DataModel::KEY_TYPE_INDEX:
					$create_index_query[] = PHP_EOL . 'CREATE INDEX IF NOT EXISTS ' . $this->_quoteName(
							'_k_' . $key_name
						) . ' ON ' . $table_name . ' (' . $key_columns . ');';
					break;
				default:
					$create_index_query[] = PHP_EOL . 'CREATE ' . $key->getType() . ' INDEX IF NOT EXISTS ' . $this->_quoteName(
							'_k_' . $key_name
						) . ' ON ' . $table_name . ' (' . $key_columns . ');';
					break;
			}
		}

		$create_index_query = implode( PHP_EOL, $create_index_query );

		$q = 'CREATE TABLE IF NOT EXISTS ' . $table_name . ' (' . PHP_EOL;
		$q .= implode( ',' . PHP_EOL, $_columns );
		$q .= implode( '', $keys );
		$q .= PHP_EOL . ') ' . $_options . ';' . $create_index_query . PHP_EOL . PHP_EOL;

		return $q;
	}

	/**
	 * @param DataModel_Definition_Property $property_definition
	 * @param bool $quote
	 * @param bool $add_table_name
	 *
	 * @return string
	 */
	protected function _getColumnName( DataModel_Definition_Property $property_definition, bool $quote = true, bool $add_table_name = true ): string
	{
		$column_name = $property_definition->getDatabaseColumnName();

		if( !$quote ) {
			return $column_name;
		}

		$column_name = $this->_quoteName( $column_name );

		if( !$add_table_name ) {
			return $column_name;
		}

		$table_name = $this->_getTableName( $property_definition->getDataModelDefinition() );

		return $table_name . '.' . $column_name;
	}

	/**
	 * @param string $name
	 *
	 * @return string
	 */
	protected function _quoteName( string $name ): string
	{
		return '`' . $name . '`';
	}

	/**
	 * @param DataModel_Definition_Model $model_definition
	 * @param bool $quote
	 *
	 * @return string
	 */
	protected function _getTableName( DataModel_Definition_Model $model_definition, bool $quote = true ): string
	{
		$table_name = strtolower( $model_definition->getDatabaseTableName() );

		if( !$quote ) {
			return $table_name;
		}

		return $this->_quoteName( $table_name );
	}

	/**
	 * @param DataModel_Definition_Property $column
	 *
	 * @return string
	 * @throws DataModel_Exception
	 */
	protected function _getSQLType( DataModel_Definition_Property $column ): string
	{
		$backend_options = $column->getBackendOptions( DataModel_Backend::TYPE_SQLITE );

		$name = $column->getName();

		if( !empty( $backend_options['column_type'] ) ) {
			return $backend_options['column_type'];
		}

		switch( $column->getType() ) {
			case DataModel::TYPE_ID:
			case DataModel::TYPE_STRING:
			case DataModel::TYPE_LOCALE:
				return 'TEXT';
			case DataModel::TYPE_ID_AUTOINCREMENT:
				if( $column->getRelatedToPropertyName() ) {
					return 'INTEGER';

				} else {
					return 'INTEGER PRIMARY KEY AUTOINCREMENT';
				}

			case DataModel::TYPE_BOOL:
			case DataModel::TYPE_INT:
				return 'INTEGER';
			case DataModel::TYPE_FLOAT:
				return 'REAL';
			case DataModel::TYPE_DATE:
			case DataModel::TYPE_DATE_TIME:
				return 'NUMERIC';
			case DataModel::TYPE_CUSTOM_DATA:
				return 'BLOB';
			default:
				throw new DataModel_Exception(
					'Unknown column type \'' . $column->getType() . '\'! Column \'' . $name . '\' ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
		}
	}

	/**
	 * @param DataModel_Definition_Model $definition
	 */
	public function helper_drop( DataModel_Definition_Model $definition ): void
	{
		$this->getDb()->execute( $this->helper_getDropCommand( $definition ) );
	}

	/**
	 * @param DataModel_Definition_Model $definition
	 *
	 * @return string
	 */
	public function helper_getDropCommand( DataModel_Definition_Model $definition ): string
	{
		$table_name = $this->_getTableName( $definition );
		$ui_prefix = '_d' . date( 'YmdHis' );

		return 'RENAME TABLE ' . $table_name . ' TO ' . $this->_quoteName( $ui_prefix . $table_name ) . '';
	}

	/**
	 * @param DataModel_Definition_Model $definition
	 *
	 */
	public function helper_update( DataModel_Definition_Model $definition ): void
	{
		$this->transactionStart();
		try {
			foreach( $this->helper_getUpdateCommand( $definition ) as $q ) {
				$this->getDb()->execute( $q );
			}
		} catch( \Exception $e ) {
			$this->transactionRollback();
			throw $e;
		}
		$this->transactionCommit();
	}

	/**
	 *
	 */
	public function transactionStart(): void
	{
		$this->getDb()->beginTransaction();
	}

	/**
	 * @param DataModel_Definition_Model $definition
	 *
	 * @return array
	 */
	public function helper_getUpdateCommand( DataModel_Definition_Model $definition ): array
	{
		$table_name = $this->_getTableName( $definition );

		$exists_cols = $this->getDb()->fetchCol( 'PRAGMA table_info(' . $table_name . ')', [], 'name' );


		$update_prefix = '_UP' . date( 'YmdHis' ) . '_';
		$updated_table_name = $this->_quoteName( $update_prefix . $this->_getTableName( $definition, false ) );
		$backup_table_name = $this->_quoteName( $update_prefix . 'b_' . $this->_getTableName( $definition, false ) );


		$create_command = $this->helper_getCreateCommand( $definition, $updated_table_name );


		$properties = $definition->getProperties();
		$actual_cols = [];
		foreach( $properties as $property ) {
			if( !$property->getCanBeTableField() ) {
				continue;
			}
			$actual_cols[$this->_getColumnName( $property, false )] = $property;
		}

		$common_cols = array_intersect( array_keys( $actual_cols ), $exists_cols );
		$_new_cols = array_diff( array_keys( $actual_cols ), $exists_cols );
		$new_cols = new DataModel_RecordData( $definition );
		foreach( $_new_cols as $new_col ) {
			foreach( $properties as $property ) {
				if( $this->_getColumnName( $property, false ) == $new_col ) {
					$new_cols->addItem( $property, $property->getDefaultValue() );

					continue 2;
				}
			}
		}
		foreach( $common_cols as $i => $col ) {
			$common_cols[$i] = $this->_quoteName( $col );
		}

		$new_cols = $this->_getRecord( $new_cols );

		$data_migration_command = 'INSERT INTO ' . $updated_table_name . '
					(' . implode( ',', $common_cols ) . ')
				SELECT
					' . implode( ',', $common_cols ) . '
				FROM ' . $table_name . ';';

		$update_default_values = '';
		if( $_new_cols ) {
			$_new_cols = [];
			foreach( $new_cols as $c => $v ) {
				$_new_cols[] = $c . '=' . $v;
			}
			$update_default_values = 'UPDATE ' . $updated_table_name . ' SET ' . implode( ',' . PHP_EOL, $_new_cols ).';';
		}


		$rename_command1 = 'ALTER TABLE ' . $table_name . ' RENAME TO ' . $backup_table_name . ' ;' . PHP_EOL;
		$rename_command2 = 'ALTER TABLE ' . $updated_table_name . ' RENAME TO  ' . $table_name . '; ';

		$update_command = [];
		$update_command[] = $create_command;
		$update_command[] = $data_migration_command;
		if( $update_default_values ) {
			$update_command[] = $update_default_values;
		}
		$update_command[] = $rename_command1;
		$update_command[] = $rename_command2;

		return $update_command;
	}

	/**
	 * @param DataModel_RecordData $record
	 * @param bool $quote
	 * @param bool $add_table_name
	 *
	 * @return array
	 */
	protected function _getRecord( DataModel_RecordData $record, bool $quote = true, bool $add_table_name = false ): array
	{
		$_record = [];

		foreach( $record as $item ) {

			$value = $item->getValue();
			if( $item->getPropertyDefinition()->getMustBeSerializedBeforeStore() ) {
				$value = $this->serialize( $value );
			}

			$_record[$this->_getColumnName(
				$item->getPropertyDefinition(), $quote, $add_table_name
			)] = $this->_getValue( $value );
		}

		return $_record;
	}

	/**
	 * @param mixed $value
	 *
	 * @return float|int|string
	 */
	protected function _getValue( mixed $value ): float|int|string
	{
		if( $value instanceof DataModel_Definition_Property ) {
			return $this->_getColumnName( $value );
		}

		if( $value === null ) {
			return 'NULL';
		}

		if( is_bool( $value ) ) {
			return $value ? 1 : 0;
		}

		if( is_int( $value ) ) {
			return $value;
		}

		if( is_float( $value ) ) {
			return $value;
		}

		if( $value instanceof Data_DateTime ) {
			$value = $value->format( 'Y-m-d H:i:s' );
		}

		if( is_array( $value ) ) {
			$value = $this->serialize( $value );
		}

		if( is_object( $value ) ) {
			$value = (string)$value;
		}
		
		return $this->getDb()->quoteString( $value );
	}

	/**
	 * @param array $data
	 *
	 * @return string
	 */
	protected function serialize( mixed $data ): string
	{
		return base64_encode( serialize( $data ) );
	}

	/**
	 *
	 */
	public function transactionRollback(): void
	{
		$this->getDb()->rollBack();
	}

	/**
	 *
	 */
	public function transactionCommit(): void
	{
		$this->getDb()->commit();
	}

	/**
	 * @param DataModel_RecordData $record
	 *
	 * @return string
	 */
	public function save( DataModel_RecordData $record ): string
	{

		$this->getDb()->execute( $this->createInsertQuery( $record ) );

		return $this->getDb()->lastInsertId();
	}

	/**
	 * @param DataModel_RecordData $record
	 *
	 *
	 * @return string
	 */
	public function createInsertQuery( DataModel_RecordData $record ): string
	{

		$data_model_definition = $record->getDataModelDefinition();

		$table_name = $this->_getTableName( $data_model_definition );

		$columns = [];
		$values = [];

		foreach( $record as $item ) {
			if(
				$item->getPropertyDefinition()->getType() == DataModel::TYPE_ID_AUTOINCREMENT &&
				!$item->getPropertyDefinition()->getRelatedToPropertyName()
			) {
				continue;
			}

			$columns[] = $this->_getColumnName( $item->getPropertyDefinition(), true, false );
			$values[] = $this->_getValue( $item->getValue() );

		}

		$columns = implode( ',' . PHP_EOL, $columns );
		$values = implode( ',' . PHP_EOL, $values );

		return 'INSERT INTO ' . $table_name . ' (' . $columns . ') VALUES (' . $values . ')';

	}

	/**
	 * @param DataModel_RecordData $record
	 * @param DataModel_Query $where
	 *
	 * @return int
	 */
	public function update( DataModel_RecordData $record, DataModel_Query $where ): int
	{
		return $this->getDb()->execute( $this->createUpdateQuery( $record, $where ) );
	}

	/**
	 * @param DataModel_RecordData $record
	 * @param DataModel_Query $where
	 *
	 * @return string
	 */
	public function createUpdateQuery( DataModel_RecordData $record, DataModel_Query $where ): string
	{
		$data_model_definition = $record->getDataModelDefinition();
		$table_name = $this->_getTableName( $data_model_definition );

		$set = [];

		foreach( $this->_getRecord( $record ) as $k => $v ) {
			$set[] = $k . '=' . $v;
		}

		$set = implode( ',' . PHP_EOL, $set );

		$where = $this->_getSqlQueryWherePart( $where->getWhere() );

		return 'UPDATE ' . $table_name . ' SET ' . PHP_EOL . $set . $where;

	}

	/**
	 * @param DataModel_Query_Where|null $query
	 *
	 * @param int $level (optional)
	 *
	 * @return string
	 */
	protected function _getSqlQueryWherePart( DataModel_Query_Where $query = null, int $level = 0 ): string
	{
		if( !$query ) {
			return '';
		}
		$res = '';

		$next_level = $level + 1;
		$tab = str_repeat( "\t", $next_level );

		foreach( $query as $qp ) {
			if( $qp instanceof DataModel_Query_Where ) {
				$res .= $tab . '(' . PHP_EOL . $this->_getSqlQueryWherePart( $qp, $next_level ) . ' ' . PHP_EOL . "\t" . ')';
				continue;
			}

			if(
				$qp === DataModel_Query::L_O_AND ||
				$qp === DataModel_Query::L_O_OR
			) {
				/**
				 * @var string $qp
				 */
				$res .= PHP_EOL . $tab . $qp . ' ' . PHP_EOL;
				continue;
			}

			/**
			 * @var DataModel_Query_Where_Expression $qp
			 */

			$prop = $qp->getProperty();


			$res .= $tab . $this->_getSQLQueryWherePart_handleExpression(
					$this->_getColumnName( $prop ), $qp->getOperator(), $qp->getValue()
				);

		}

		if( $res && !$level ) {
			$res = PHP_EOL . 'WHERE' . PHP_EOL . $res . PHP_EOL;
		}

		return $res;
	}

	/**
	 * @param string $item
	 * @param string $operator
	 * @param mixed $value
	 *
	 * @return string
	 */
	protected function _getSQLQueryWherePart_handleExpression( string $item, string $operator, mixed $value ): string
	{
		$res = '';

		if( is_array( $value ) ) {
			$sq = [];

			foreach( $value as $v ) {

				$sq[] = "\t\t" . $item . $this->_getSQLQueryWherePart_handleOperator( $operator, $v );
			}

			$res .= '(' . PHP_EOL . implode( ' OR' . PHP_EOL, $sq ) . PHP_EOL . "\t" . ') ';
		} else {
			$res .= $item . $this->_getSQLQueryWherePart_handleOperator( $operator, $value );

		}

		return $res;

	}

	/**
	 * @param string $operator
	 * @param mixed $value
	 *
	 * @return string
	 * @throws DataModel_Backend_Exception
	 */
	protected function _getSQLQueryWherePart_handleOperator( string $operator, mixed $value ): string
	{
		$value = $this->_getValue( $value );
		$res = '';

		switch( $operator ) {
			case DataModel_Query::O_EQUAL:
				if( $value === 'NULL' ) {
					$res .= ' IS NULL';
				} else {
					$res .= '=' . $value;
				}
				break;
			case DataModel_Query::O_NOT_EQUAL:
				if( $value === 'NULL' ) {
					$res .= ' IS NOT NULL';
				} else {
					$res .= '<>' . $value;
				}
				break;
			case DataModel_Query::O_LIKE:
				$res .= ' LIKE ' . $value;
				break;
			case DataModel_Query::O_NOT_LIKE:
				$res .= ' NOT LIKE ' . $value;
				break;
			case DataModel_Query::O_GREATER_THAN:
				$res .= '>' . $value . ' ';
				break;
			case DataModel_Query::O_LESS_THAN:
				$res .= '<' . $value . ' ';
				break;
			case DataModel_Query::O_GREATER_THAN_OR_EQUAL:
				$res .= '>=' . $value . ' ';
				break;
			case DataModel_Query::O_LESS_THAN_OR_EQUAL:
				$res .= '<=' . $value . ' ';
				break;

			default:
				throw new DataModel_Backend_Exception(
					'Unknown operator ' . $operator . '! ', DataModel_Backend_Exception::CODE_BACKEND_ERROR
				);


		}

		return $res;
	}

	/**
	 * @param DataModel_Query $where
	 *
	 * @return int
	 */
	public function delete( DataModel_Query $where ): int
	{
		return $this->getDb()->execute( $this->createDeleteQuery( $where ) );
	}

	/**
	 * @param DataModel_Query $where
	 *
	 * @return string
	 */
	public function createDeleteQuery( DataModel_Query $where ): string
	{
		$table_name = $this->_getTableName( $where->getDataModelDefinition() );

		return 'DELETE FROM ' . $table_name . '' . $this->_getSqlQueryWherePart( $where->getWhere() );
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return int
	 */
	public function getCount( DataModel_Query $query ): int
	{
		return (int)$this->getDb()->fetchOne( $this->createCountQuery( $query ) );
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	public function createCountQuery( DataModel_Query $query ): string
	{

		if( !$query->getSelect() ) {
			$id_properties = [];
			foreach( $query->getDataModelDefinition()->getIdProperties() as $id_property ) {
				$id_properties[] = $id_property;
			}

			$query->setSelect( $id_properties );
		}

		return 'SELECT count(*) FROM (' . $this->createSelectQuery( $query ) . ')';
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	protected function _getSQLQueryTableName( DataModel_Query $query ): string
	{
		$main_model_definition = $query->getDataModelDefinition();

		return $this->_getTableName( $main_model_definition );

	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 * @throws DataModel_Backend_Exception
	 */
	protected function _getSQLQueryJoinPart( DataModel_Query $query ): string
	{
		$join_qp = '';

		foreach( $query->getRelations() as $relation ) {


			$r_table_name = $this->_getTableName( $relation->getRelatedDataModelDefinition() );

			/** @noinspection PhpSwitchCanBeReplacedWithMatchExpressionInspection */
			switch( $relation->getJoinType() ) {
				case DataModel_Query::JOIN_TYPE_LEFT_JOIN:
					$join_qp .= PHP_EOL . "\t\t" . 'JOIN ' . $r_table_name . ' ON' . PHP_EOL;
					break;
				case DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN:
					$join_qp .= PHP_EOL . "\t\t" . 'LEFT OUTER JOIN ' . $r_table_name . ' ON' . PHP_EOL;
					break;
				default:
					throw new DataModel_Backend_Exception(
						'MySQL backend: unknown join type \'' . $relation->getJoinType() . '\'',
						DataModel_Backend_Exception::CODE_BACKEND_ERROR
					);
			}

			$j = [];
			foreach( $relation->getJoinBy() as $join_by ) {

				if( $join_by instanceof DataModel_Definition_Relation_Join_Item ) {
					$j[] = "\t\t\t" . $this->_getColumnName( $join_by->getRelatedProperty() ) . ' = ' . $this->_getColumnName( $join_by->getThisProperty() );
				}

				if( $join_by instanceof DataModel_Definition_Relation_Join_Condition ) {

					$value = $this->_getValue( $join_by->getValue() );
					$operator = $this->_getSQLQueryWherePart_handleOperator( $join_by->getOperator(), $value );

					$j[] = "\t\t\t" . $this->_getColumnName( $join_by->getRelatedProperty() ) . $operator . $value;

				}
			}


			$join_qp .= implode( ' AND ' . PHP_EOL, $j );
		}

		return $join_qp;
	}

	/**
	 * @param DataModel_Query|null $query
	 *
	 * @return string
	 */
	protected function _getSqlQueryGroupPart( ?DataModel_Query $query = null ): string
	{
		$group_by = $query->getGroupBy();
		if( !$group_by ) {
			return '';
		}

		$group_by_qp = [];

		foreach( $group_by as $val ) {
			/**
			 * @var DataModel_Query_Select_Item $val
			 */
			if( $val instanceof DataModel_Definition_Property ) {
				/**
				 * @var DataModel_Definition_Property $val
				 */
				$val = $this->_getColumnName( $val );
			} else if( $val instanceof DataModel_Query_Select_Item ) {
				$val = $val->getSelectAs();
			}

			$group_by_qp[] = $val;
		}

		return PHP_EOL . 'GROUP BY' . PHP_EOL . "\t" . implode( ',' . PHP_EOL . "\t", $group_by_qp ) . PHP_EOL;
	}

	/**
	 * @param DataModel_Query_Having|null $query
	 *
	 * @param int $level (optional)
	 *
	 * @return string
	 */
	protected function _getSqlQueryHavingPart( ?DataModel_Query_Having $query = null, int $level = 0 ): string
	{
		if( !$query ) {
			return '';
		}
		$res = '';

		$next_level = $level + 1;
		$tab = str_repeat( "\t", $next_level );

		foreach( $query as $qp ) {
			if( $qp instanceof DataModel_Query_Having ) {
				$res .= $tab . '(' . PHP_EOL . $this->_getSqlQueryHavingPart( $qp, $next_level ) . ' ' . PHP_EOL . "\t" . ')';
				continue;
			}

			if(
				$qp === DataModel_Query::L_O_AND ||
				$qp === DataModel_Query::L_O_OR
			) {
				/**
				 * @var string $qp
				 */
				$res .= PHP_EOL . $tab . $qp . PHP_EOL . ' ';
				continue;
			}

			/**
			 * @var DataModel_Query_Having_Expression $qp
			 */

			/**
			 * @var DataModel_Definition_Property $prop
			 */
			$item = $qp->getProperty()->getSelectAs();


			$res .= $tab . $this->_getSQLQueryWherePart_handleExpression(
					$item, $qp->getOperator(), $qp->getValue()
				);
		}

		if( $res && !$level ) {
			$res = PHP_EOL . 'HAVING' . PHP_EOL . $res . PHP_EOL;
		}

		return $res;
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	public function createSelectQuery( DataModel_Query $query ): string
	{

		return 'SELECT' . PHP_EOL
			. "\t" . $this->_getSQLQuerySelectPart( $query ) . PHP_EOL
			. 'FROM' . PHP_EOL
			. "\t" . $this->_getSQLQueryTableName( $query )
			. $this->_getSQLQueryJoinPart( $query )

			. $this->_getSqlQueryWherePart( $query->getWhere() )
			. $this->_getSqlQueryGroupPart( $query )
			. $this->_getSqlQueryHavingPart( $query->getHaving() )
			. $this->_getSqlQueryOrderByPart( $query )
			. $this->_getSqlQueryLimitPart( $query );

	}

	/**
	 *
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	protected function _getSQLQuerySelectPart( DataModel_Query $query ): string
	{
		$columns_qp = [];


		$mapper = function( DataModel_Definition_Property $property ) {
			return $this->_getColumnName( $property );
		};

		foreach( $query->getSelect() as $item ) {
			$property = $item->getItem();
			$select_as = $item->getSelectAs();

			if( $property instanceof DataModel_Definition_Property ) {
				$columns_qp[] = $this->_getColumnName( $property ) . ' AS ' . $this->_quoteName( $select_as ) . '';

				continue;
			}

			if( $property instanceof DataModel_Query_Select_Item_Expression ) {
				$backend_function_call = $property->toString( $mapper );

				$columns_qp[] = $backend_function_call . ' AS ' . $this->_quoteName( $select_as ) . '';
			}

		}

		return implode( ',' . PHP_EOL . "\t", $columns_qp );
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	protected function _getSqlQueryOrderByPart( DataModel_Query $query ): string
	{
		$order_by = $query->getOrderBy();

		if( !$order_by ) {
			return '';
		}

		$order_qp = [];

		foreach( $order_by as $ob ) {
			/**
			 * @var DataModel_Query_OrderBy_Item $ob
			 */
			$item = $ob->getItem();
			if( $item instanceof DataModel_Definition_Property ) {
				$item = $this->_getColumnName( $item );
			} else if( $item instanceof DataModel_Query_Select_Item ) {
				$item = $item->getSelectAs();
			}
			$order_by_desc = $ob->getDesc();


			/**
			 * @var string $item
			 */
			if( $order_by_desc ) {
				$order_qp[] = $item . ' DESC';
			} else {
				$order_qp[] = $item . ' ASC';
			}
		}

		if( !$order_qp ) {
			return '';
		}

		return PHP_EOL . 'ORDER BY' . PHP_EOL . "\t" . implode( ',' . PHP_EOL . "\t", $order_qp ) . PHP_EOL;

	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	protected function _getSqlQueryLimitPart( DataModel_Query $query ): string
	{
		$limit_qp = '';

		$offset = (int)$query->getOffset();
		$limit = (int)$query->getLimit();

		if( $limit ) {
			if( $offset ) {
				$limit_qp = PHP_EOL . 'LIMIT ' . $offset . ',' . $limit . PHP_EOL;
			} else {
				$limit_qp = PHP_EOL . 'LIMIT ' . $limit . PHP_EOL;
			}
		}

		return $limit_qp;
	}

	/**
	 * @param DataModel_Query $query
	 * @param string $fetch_method
	 *
	 * @return mixed
	 */
	protected function _fetch( DataModel_Query $query, string $fetch_method ): mixed
	{

		$data = $this->getDb()->$fetch_method(
			$this->createSelectQuery( $query )
		);

		if( !is_array( $data ) ) {
			return $data;
		}

		return $this->validateResultData( $query, $fetch_method, $data );
	}

	/**
	 * @param string $string
	 *
	 * @return mixed
	 */
	protected function unserialize( string $string ): mixed
	{
		$data = base64_decode( $string );

		return unserialize( $data );
	}
}