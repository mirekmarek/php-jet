<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
class DataModel_Backend_MySQL extends DataModel_Backend
{

	/**
	 * @var array
	 */
	protected static $valid_key_types = [
		DataModel::KEY_TYPE_PRIMARY,
		DataModel::KEY_TYPE_INDEX,
		DataModel::KEY_TYPE_UNIQUE,
	];
	/**
	 * @var DataModel_Backend_MySQL_Config
	 */
	protected $config;
	/**
	 *
	 * @var Db_BackendInterface
	 */
	private $_db_read = null;
	/**
	 *
	 * @var Db_BackendInterface
	 */
	private $_db_write = null;

	/**
	 *
	 */
	public function initialize()
	{
		$this->_db_read = Db::get( $this->config->getConnectionRead() );
		$this->_db_write = Db::get( $this->config->getConnectionWrite() );
	}

	/**
	 * @param DataModel_Definition_Model $definition
	 */
	public function helper_create( DataModel_Definition_Model $definition )
	{
		$this->_db_write->execCommand( $this->helper_getCreateCommand( $definition ) );
	}

	/**
	 * @param DataModel_Definition_Model $definition
	 * @param string|null                $force_table_name (optional)
	 *
	 * @return string
	 */
	public function helper_getCreateCommand( DataModel_Definition_Model $definition, $force_table_name = null )
	{

		$options = [];

		$options['ENGINE'] = $this->config->getEngine();
		$options['DEFAULT CHARSET'] = $this->config->getDefaultCharset();
		$options['COLLATE'] = $this->config->getCollate();

		$_options = [];

		foreach( $options as $o => $v ) {
			$_options[] = $o.'='.$v;
		}

		$_options = implode( ' ', $_options );


		$_columns = [];

		foreach( $definition->getProperties() as $property ) {
			if( !$property->getCanBeTableField() ) {
				continue;
			}

			$_columns[] = JET_TAB.$this->_getColumnName( $property, true, false ).' '.$this->_getSQLType( $property );
		}

		$_keys = [];
		foreach( $definition->getKeys() as $key_name => $key ) {

			$key_columns = [];
			foreach( $key->getPropertyNames() as $property_name ) {
				$property = $definition->getProperty( $property_name );
				$key_columns[] = $this->_getColumnName( $property, true, false );
			}

			$key_columns = implode( ', ', $key_columns );

			switch( $key->getType() ) {
				case DataModel::KEY_TYPE_PRIMARY:
					foreach( $key->getPropertyNames() as $property_name ) {
						$property = $definition->getProperty( $property_name );

						if(
							$property->getType()==DataModel::TYPE_ID_AUTOINCREMENT &&
							!$property->getRelatedToPropertyName()
						) {
							$key_columns = $this->_getColumnName( $property, true, false );
							break;
						}
					}

					$_keys[$key_name] = JET_EOL.JET_TAB.',PRIMARY KEY ('.$key_columns.')';
					break;
				case DataModel::KEY_TYPE_INDEX:
					$_keys[$key_name] = JET_EOL.JET_TAB.',KEY '.$this->_quoteName( $key_name ).' ('.$key_columns.')';
					break;
				default:
					$_keys[$key_name] = JET_EOL.JET_TAB.','.$key->getType().' KEY '.$this->_quoteName(
							$key_name
						).' ('.$key_columns.')';
					break;
			}
		}

		$table_name = $force_table_name ? $force_table_name : $this->_getTableName( $definition );

		$q = 'CREATE TABLE IF NOT EXISTS '.$table_name.' ('.JET_EOL;
		$q .= implode( ','.JET_EOL, $_columns );
		$q .= implode( '', $_keys );
		$q .= JET_EOL.') '.$_options.';'.JET_EOL.JET_EOL;

		return $q;
	}

	/**
	 * @param DataModel_Definition_Property $property_definition
	 * @param bool                          $quote
	 * @param bool                          $add_table_name
	 *
	 * @return string
	 */
	protected function _getColumnName( DataModel_Definition_Property $property_definition, $quote = true, $add_table_name = true )
	{
		$column_name = $property_definition->getDatabaseColumnName();

		if( !$quote ) {
			return $column_name;
		}

		$column_name = $this->_quoteName( $column_name );

		if( !$add_table_name ) {
			return $column_name;
		}

		$table_name = $this->_getTableName( $property_definition->getDataModelDefinition(), true );

		return $table_name.'.'.$column_name;
	}

	/**
	 * @param string $name
	 *
	 * @return string
	 */
	protected function _quoteName( $name )
	{
		return '`'.substr( $name, 0, 64 ).'`';
	}

	/**
	 * @param DataModel_Definition_Model $model_definition
	 * @param bool                       $quote
	 *
	 * @return string
	 */
	protected function _getTableName( DataModel_Definition_Model $model_definition, $quote = true )
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
	 * @throws DataModel_Exception
	 * @throws DataModel_Backend_Exception
	 * @return string
	 */
	protected function _getSQLType( DataModel_Definition_Property $column )
	{
		$backend_options = $column->getBackendOptions( 'MySQL' );

		$name = $column->getName();

		$default_value = $column->getDefaultValue();


		if(
			!empty( $backend_options['column_type'] )
		) {
			return $backend_options['column_type'];
		}

		switch( $column->getType() ) {
			case DataModel::TYPE_ID:
				return 'varchar(64) COLLATE utf8_bin NOT NULL DEFAULT \'\'';
				break;
			case DataModel::TYPE_ID_AUTOINCREMENT:
				if( $column->getRelatedToPropertyName() ) {
					return 'bigint UNSIGNED';

				} else {
					return 'bigint UNSIGNED auto_increment';
				}

				break;

			case DataModel::TYPE_STRING:
				$max_len = (int)$column->getMaxLen();

				if( $max_len<=255 ) {
					if( $column->getIsId() ) {
						return 'varchar('.( (int)$max_len ).') COLLATE utf8_bin NOT NULL  DEFAULT \'\'';
					} else {
						return 'varchar('.( (int)$max_len ).') DEFAULT '.$this->_getValue( $default_value );
					}
				}

				if( $max_len<=65535 ) {
					return 'text';
				}

				return 'longtext';
				break;
			case DataModel::TYPE_BOOL:
				return 'tinyint(1) DEFAULT '.( $default_value ? 1 : 0 );
				break;
			case DataModel::TYPE_INT:
				return 'int DEFAULT '.(int)$default_value;
				break;
			case DataModel::TYPE_FLOAT:
				return 'float DEFAULT '.(float)$default_value;
				break;
			case DataModel::TYPE_LOCALE:
				return 'varchar(20) COLLATE utf8_bin NOT NULL';
				break;
			case DataModel::TYPE_DATE:
				return 'date DEFAULT NULL';
				break;
			case DataModel::TYPE_DATE_TIME:
				return 'datetime DEFAULT NULL';
				break;
			case DataModel::TYPE_ARRAY:
				return 'longtext';
				break;
			default:
				throw new DataModel_Exception(
					'Unknown column type \''.$column->getType().'\'! Column \''.$name.'\' ',
					DataModel_Exception::CODE_DEFINITION_NONSENSE
				);
				break;

		}
	}

	/**
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	protected function _getValue( $value )
	{
		if( $value instanceof DataModel_Definition_Property ) {
			return $this->_getColumnName( $value );
		}

		if( $value===null ) {
			return 'NULL';
		}

		if( is_bool( $value ) ) {
			return $value ? 1 : 0;
		}

		if( is_int( $value ) ) {
			return (int)$value;
		}

		if( is_float( $value ) ) {
			return (float)$value;
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

		return $this->_db_read->quote( $value );
	}

	/**
	 * @param DataModel_Definition_Model $definition
	 */
	public function helper_drop( DataModel_Definition_Model $definition )
	{
		$this->_db_write->execCommand( $this->helper_getDropCommand( $definition ) );
	}

	/**
	 * @param DataModel_Definition_Model $definition
	 *
	 * @return string
	 */
	public function helper_getDropCommand( DataModel_Definition_Model $definition )
	{
		$table_name = $this->_getTableName( $definition, false );
		$ui_prefix = '_d'.date( 'YmdHis' );

		return 'RENAME TABLE '.$this->_quoteName( $table_name ).' TO '.$this->_quoteName( $ui_prefix.$table_name ).'';
	}

	/**
	 * @param DataModel_Definition_Model $definition
	 *
	 * @throws \Exception|Exception
	 */
	public function helper_update( DataModel_Definition_Model $definition )
	{
		$this->transactionStart();
		try {
			foreach( $this->helper_getUpdateCommand( $definition ) as $q ) {
				$this->_db_write->execCommand( $q );
			}
		} catch( Exception $e ) {
			$this->transactionRollback();
			throw $e;
		}
		$this->transactionCommit();
	}

	/**
	 *
	 */
	public function transactionStart()
	{
		$this->_db_write->beginTransaction();
	}

	/**
	 * @param DataModel_Definition_Model $definition
	 *
	 * @return array
	 */
	public function helper_getUpdateCommand( DataModel_Definition_Model $definition )
	{
		$table_name = $this->_getTableName( $definition );

		$exists_cols = $this->_db_write->fetchCol( 'DESCRIBE '.$table_name.'' );

		$update_prefix = '_UP'.date( 'YmdHis' ).'_';
		$updated_table_name = $this->_quoteName( $update_prefix.$this->_getTableName( $definition, false ) );
		$backup_table_name = $this->_quoteName( $update_prefix.'b_'.$this->_getTableName( $definition, false ) );

		$create_command = $this->helper_getCreateCommand( $definition, $updated_table_name );

		$properties = $definition->getProperties();
		$actual_cols = [];
		foreach( $properties as $property ) {
			/**
			 * @var DataModel_Definition_Property $property
			 */
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
				if( $this->_getColumnName( $property, false )==$new_col ) {
					$new_cols->addItem( $property, $property->getDefaultValue() );

					continue 2;
				}
			}
		}
		foreach( $common_cols as $i => $col ) {
			$common_cols[$i] = $this->_quoteName( $col );
		}

		$data_migration_command = 'INSERT INTO '.$updated_table_name.' ('.implode( ',', $common_cols ).') SELECT '.implode( ',', $common_cols ).' FROM '.$table_name.';';

		$update_default_values = '';

		if( $_new_cols ) {
			$new_cols = $this->_getRecord( $new_cols );
			$_new_cols = [];
			foreach( $new_cols as $c => $v ) {
				$_new_cols[] = $c.'='.$v;
			}

			$update_default_values = 'UPDATE '.$updated_table_name.' SET '.implode( ', ', $_new_cols );
		}


		$rename_command1 = 'RENAME TABLE '.$table_name.' TO '.$backup_table_name.';';
		$rename_command2 = 'RENAME TABLE '.$updated_table_name.' TO  '.$table_name.';';

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
	 * @param bool                 $quote
	 * @param bool                 $add_table_name
	 *
	 * @return array
	 */
	protected function _getRecord( DataModel_RecordData $record, $quote = true, $add_table_name = false )
	{
		$_record = [];

		foreach( $record as $item ) {
			/**
			 * @var DataModel_RecordData_Item $item
			 */

			$_record[$this->_getColumnName(
				$item->getPropertyDefinition(), $quote, $add_table_name
			)] = $this->_getValue( $item->getValue() );
		}

		return $_record;
	}

	/**
	 *
	 */
	public function transactionRollback()
	{
		$this->_db_write->rollBack();
	}

	/**
	 *
	 */
	public function transactionCommit()
	{
		$this->_db_write->commit();
	}

	/**
	 * @param DataModel_RecordData $record
	 *
	 * @return mixed
	 */
	public function save( DataModel_RecordData $record )
	{
		$this->_db_write->execCommand( $this->createInsertQuery( $record ) );

		return $this->_db_write->lastInsertId();
	}

	/**
	 * @param DataModel_RecordData $record
	 *
	 *
	 * @return string
	 */
	public function createInsertQuery( DataModel_RecordData $record )
	{

		$data_model_definition = $record->getDataModelDefinition();

		$table_name = $this->_getTableName( $data_model_definition );

		$set = [];

		foreach( $this->_getRecord( $record ) as $k => $v ) {
			$set[] = $k.'='.$v;
		}

		$set = implode( ','.JET_EOL, $set );


		return 'INSERT INTO '.$table_name.' SET '.JET_EOL.$set;
	}

	/**
	 * @param DataModel_RecordData $record
	 * @param DataModel_Query      $where
	 *
	 * @return int
	 */
	public function update( DataModel_RecordData $record, DataModel_Query $where )
	{

		return $this->_db_write->execCommand( $this->createUpdateQuery( $record, $where ) );
	}

	/**
	 * @param DataModel_RecordData $record
	 * @param DataModel_Query      $where
	 *
	 * @return string
	 */
	public function createUpdateQuery( DataModel_RecordData $record, DataModel_Query $where )
	{
		$data_model_definition = $record->getDataModelDefinition();
		$table_name = $this->_getTableName( $data_model_definition );

		$set = [];

		foreach( $this->_getRecord( $record ) as $k => $v ) {
			$set[] = $k.'='.$v;
		}

		$set = implode( ','.JET_EOL, $set );

		$where = $this->_getSqlQueryWherePart( $where->getWhere() );

		return 'UPDATE '.$table_name.' SET '.JET_EOL.$set.$where;

	}

	/**
	 * @param DataModel_Query_Where $query
	 *
	 * @param int                   $level (optional)
	 *
	 * @return string
	 */
	protected function _getSqlQueryWherePart( DataModel_Query_Where $query = null, $level = 0 )
	{
		if( !$query ) {
			return '';
		}
		$res = '';

		$next_level = $level+1;
		$tab = str_repeat( JET_TAB, $next_level );

		foreach( $query as $qp ) {
			if( $qp instanceof DataModel_Query_Where ) {
				/**
				 * @var DataModel_Query_Where $qp
				 */
				$res .= $tab.'('.JET_EOL.$this->_getSqlQueryWherePart( $qp, $next_level ).JET_EOL.JET_TAB.')';
				continue;
			}

			if(
				$qp===DataModel_Query::L_O_AND ||
				$qp===DataModel_Query::L_O_OR
			) {
				/**
				 * @var string $qp
				 */
				$res .= JET_EOL.$tab.$qp.JET_EOL;
				continue;
			}

			/**
			 * @var DataModel_Query_Where_Expression $qp
			 */

			/**
			 * @var DataModel_Definition_Property $prop
			 */
			$prop = $qp->getProperty();


			$res .= $tab.$this->_getSQLQueryWherePart_handleExpression(
					$this->_getColumnName( $prop ), $qp->getOperator(), $qp->getValue()
				);

		}

		if( $res && !$level ) {
			$res = JET_EOL.'WHERE'.JET_EOL.$res.JET_EOL;
		}

		return $res;
	}

	/**
	 * @param string $item
	 * @param string $operator
	 * @param mixed  $value
	 *
	 * @return string
	 */
	protected function _getSQLQueryWherePart_handleExpression( $item, $operator, $value )
	{
		$res = '';

		if( is_array( $value ) ) {

			$sq = [];

			/**
			 * @var array $value
			 */
			foreach( $value as $v ) {

				$sq[] = JET_TAB.JET_TAB.$item.$this->_getSQLQueryWherePart_handleOperator( $operator, $v );
			}

			$res .= '('.JET_EOL.implode( ' OR '.JET_EOL, $sq ).JET_EOL.JET_TAB.') ';
		} else {
			$res .= $item.$this->_getSQLQueryWherePart_handleOperator( $operator, $value );

		}

		return $res;

	}

	/**
	 * @param string $operator
	 * @param mixed  $value
	 *
	 * @throws DataModel_Backend_Exception
	 * @return string
	 */
	protected function _getSQLQueryWherePart_handleOperator( $operator, $value )
	{

		$value = $this->_getValue( $value );

		$res = '';

		switch( $operator ) {
			case DataModel_Query::O_EQUAL:
				if( $value==='NULL' ) {
					$res .= ' IS NULL';
				} else {
					$res .= '='.$value;
				}
				break;
			case DataModel_Query::O_NOT_EQUAL:
				if( $value==='NULL' ) {
					$res .= ' IS NOT NULL';
				} else {
					$res .= '<>'.$value;
				}
				break;
			case DataModel_Query::O_LIKE:
				$res .= ' LIKE '.$value;
				break;
			case DataModel_Query::O_NOT_LIKE:
				$res .= ' NOT LIKE '.$value;
				break;
			case DataModel_Query::O_GREATER_THAN:
				$res .= '>'.$value;
				break;
			case DataModel_Query::O_LESS_THAN:
				$res .= '<'.$value;
				break;
			case DataModel_Query::O_GREATER_THAN_OR_EQUAL:
				$res .= '>='.$value;
				break;
			case DataModel_Query::O_LESS_THAN_OR_EQUAL:
				$res .= '<='.$value;
				break;

			default:
				throw new DataModel_Backend_Exception(
					'Unknown operator '.$operator.'! ', DataModel_Backend_Exception::CODE_BACKEND_ERROR
				);


		}

		return $res;
	}

	/**
	 * @param DataModel_Query $where
	 *
	 * @return int
	 */
	public function delete( DataModel_Query $where )
	{
		return $this->_db_write->execCommand( $this->createDeleteQuery( $where ) );
	}

	/**
	 * @param DataModel_Query $where
	 *
	 * @return string
	 */
	public function createDeleteQuery( DataModel_Query $where )
	{
		$table_name = $this->_getTableName( $where->getMainDataModelDefinition() );

		return 'DELETE FROM '.$table_name.''.$this->_getSqlQueryWherePart( $where->getWhere() );
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return int
	 */
	public function getCount( DataModel_Query $query )
	{

		return (int)$this->_db_read->fetchOne( $this->createCountQuery( $query ) );
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	public function createCountQuery( DataModel_Query $query )
	{

		$id_properties = [];
		foreach( $query->getMainDataModelDefinition()->getIdProperties() as $id_property ) {
			$id_properties[] = $this->_getColumnName( $id_property );
		}

		$id_properties = implode( ', ', $id_properties );

		return 'SELECT count(DISTINCT '.$id_properties.') FROM'.JET_EOL.JET_TAB.$this->_getSQLQueryTableName(
			$query
		).$this->_getSQLQueryJoinPart( $query ).$this->_getSqlQueryWherePart(
			$query->getWhere()
		).$this->_getSqlQueryHavingPart( $query->getHaving() );
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	protected function _getSQLQueryTableName( DataModel_Query $query )
	{
		return $this->_getTableName( $query->getMainDataModelDefinition() );

	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @throws DataModel_Backend_Exception
	 * @return string
	 */
	protected function _getSQLQueryJoinPart( DataModel_Query $query )
	{
		$join_qp = '';

		foreach( $query->getRelations() as $relation ) {


			$r_table_name = $this->_getTableName( $relation->getRelatedDataModelDefinition() );


			switch( $relation->getJoinType() ) {
				case DataModel_Query::JOIN_TYPE_LEFT_JOIN:
					$join_qp .= JET_EOL.JET_TAB.JET_TAB.'JOIN '.$r_table_name.' ON'.JET_EOL;
					break;
				case DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN:
					$join_qp .= JET_EOL.JET_TAB.JET_TAB.'LEFT OUTER JOIN '.$r_table_name.' ON'.JET_EOL;
					break;
				default:
					throw new DataModel_Backend_Exception(
						'MySQL backend: unknown join type \''.$relation->getJoinType().'\'',
						DataModel_Backend_Exception::CODE_BACKEND_ERROR
					);
					break;
			}

			$j = [];


			foreach( $relation->getJoinBy() as $join_by ) {


				if($join_by instanceof DataModel_Definition_Relation_Join_Item) {
					$j[] = JET_TAB.JET_TAB.JET_TAB.$this->_getColumnName( $join_by->getRelatedProperty() ).' = '.$this->_getColumnName( $join_by->getThisProperty() );
				}

				if($join_by instanceof DataModel_Definition_Relation_Join_Condition) {

					$value = $this->_getValue($join_by->getValue());
					$operator = $this->_getSQLQueryWherePart_handleOperator( $join_by->getOperator(), $value );

					$j[] = JET_TAB.JET_TAB.JET_TAB.$this->_getColumnName( $join_by->getRelatedProperty() ).$operator.$value;

				}

			}


			$join_qp .= implode( ' AND'.JET_EOL, $j );
		}

		return $join_qp;
	}

	/**
	 * @param DataModel_Query_Having $query
	 *
	 * @param int                    $level (optional)
	 *
	 * @return string
	 */
	protected function _getSqlQueryHavingPart( DataModel_Query_Having $query = null, $level = 0 )
	{
		if( !$query ) {
			return '';
		}
		$res = '';

		$next_level = $level+1;
		$tab = str_repeat( JET_TAB, $next_level );

		foreach( $query as $qp ) {
			if( $qp instanceof DataModel_Query_Having ) {
				/**
				 * @var DataModel_Query_Having $qp
				 */
				$res .= $tab.'('.JET_EOL.$this->_getSqlQueryHavingPart( $qp, $next_level ).JET_EOL.JET_TAB.')';
				continue;
			}

			if(
				$qp===DataModel_Query::L_O_AND ||
				$qp===DataModel_Query::L_O_OR
			) {
				/**
				 * @var string $qp
				 */
				$res .= JET_EOL.$tab.$qp.JET_EOL;
				continue;
			}

			/**
			 * @var DataModel_Query_Having_Expression $qp
			 */

			/**
			 * @var DataModel_Definition_Property $prop
			 */
			$item = $qp->getProperty()->getSelectAs();


			$res .= $tab.$this->_getSQLQueryWherePart_handleExpression(
					$this->_quoteName( $item ), $qp->getOperator(), $qp->getValue()
				);
		}

		if( $res && !$level ) {
			$res = JET_EOL.'HAVING'.JET_EOL.$res.JET_EOL;
		}

		return $res;
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	public function fetchAll( DataModel_Query $query )
	{
		return $this->_fetch( $query, 'fetchAll' );
	}

	/**
	 * @param DataModel_Query $query
	 * @param string          $fetch_method
	 *
	 * @return mixed
	 */
	protected function _fetch( DataModel_Query $query, $fetch_method )
	{

		$data = $this->_db_read->$fetch_method(
			$this->createSelectQuery( $query )
		);

		if( !is_array( $data ) ) {
			return $data;
		}

		return $this->validateResultData( $query, $fetch_method, $data );
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	public function createSelectQuery( DataModel_Query $query )
	{

		return 'SELECT'.JET_EOL
			.JET_TAB.$this->_getSQLQuerySelectPart( $query ).JET_EOL
			.'FROM'.JET_EOL
			.JET_TAB.$this->_getSQLQueryTableName( $query )
			.$this->_getSQLQueryJoinPart( $query )
			.$this->_getSqlQueryWherePart( $query->getWhere() )
			.$this->_getSqlQueryGroupPart( $query )
			.$this->_getSqlQueryHavingPart( $query->getHaving() )
			.$this->_getSqlQueryOrderByPart( $query )
			.$this->_getSqlQueryLimitPart( $query );

	}

	/**
	 *
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	protected function _getSQLQuerySelectPart( DataModel_Query $query )
	{
		$columns_qp = [];


		$mapper = function( DataModel_Definition_Property $property ) {
			return $this->_getColumnName( $property );
		};

		foreach( $query->getSelect() as $item ) {
			/**
			 * @var DataModel_Query_Select_Item $item
			 */
			$property = $item->getItem();
			$select_as = $item->getSelectAs();

			if( $property instanceof DataModel_Definition_Property ) {
				/**
				 * @var DataModel_Definition_Property $property
				 */
				$columns_qp[] = $this->_getColumnName( $property ).' AS '.$this->_quoteName( $select_as ).'';

				continue;
			}

			if( $property instanceof DataModel_Query_Select_Item_Expression ) {

				/**
				 * @var DataModel_Query_Select_Item_Expression $property
				 */

				$backend_function_call = $property->toString( $mapper );

				$columns_qp[] = $backend_function_call.' AS '.$this->_quoteName( $select_as ).'';
				continue;
			}

		}

		return implode( ','.JET_EOL.JET_TAB, $columns_qp );
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	protected function _getSqlQueryGroupPart( DataModel_Query $query = null )
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
				$val = $this->_quoteName( $val->getSelectAs() );
			}

			$group_by_qp[] = $val;
		}

		$group_by_qp = JET_EOL.'GROUP BY'.JET_EOL.JET_TAB.implode( ','.JET_EOL.JET_TAB, $group_by_qp ).JET_EOL;

		return $group_by_qp;
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	protected function _getSqlQueryOrderByPart( DataModel_Query $query )
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
				$item = $this->_quoteName( $item->getSelectAs() );
			}
			$order_by_desc = $ob->getDesc();


			/** @var string $item */
			if( $order_by_desc ) {
				$order_qp[] = $item.' DESC';
			} else {
				$order_qp[] = $item.' ASC';
			}
		}

		if( !$order_qp ) {
			return '';
		}

		return JET_EOL.'ORDER BY'.JET_EOL.JET_TAB.implode( ','.JET_EOL.JET_TAB, $order_qp ).JET_EOL;

	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	protected function _getSqlQueryLimitPart( DataModel_Query $query )
	{
		$limit_qp = '';

		$offset = (int)$query->getOffset();
		$limit = (int)$query->getLimit();

		if( $limit ) {
			if( $offset ) {
				$limit_qp = JET_EOL.'LIMIT '.$offset.','.$limit.JET_EOL;
			} else {
				$limit_qp = JET_EOL.'LIMIT '.$limit.JET_EOL;
			}
		}

		return $limit_qp;
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	public function fetchAssoc( DataModel_Query $query )
	{
		return $this->_fetch( $query, 'fetchAssoc' );
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	public function fetchPairs( DataModel_Query $query )
	{
		return $this->_fetch( $query, 'fetchPairs' );
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	public function fetchRow( DataModel_Query $query )
	{
		return $this->_fetch( $query, 'fetchRow' );
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	public function fetchOne( DataModel_Query $query )
	{
		return $this->_fetch( $query, 'fetchOne' );
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	public function fetchCol( DataModel_Query $query )
	{
		$data = $this->_db_read->fetchCol(
			$this->createSelectQuery( $query )
		);

		if( !is_array( $data ) ) {
			return $data;
		}


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

				if( $property->getMustBeSerializedBeforeStore() ) {
					$data[$i] = $this->unserialize( $data[$i] );
				}

				$property->checkValueType( $data[$i] );

				break;
			}
		}

		return $data;
	}

}