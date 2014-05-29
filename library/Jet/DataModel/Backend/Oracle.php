<?php
/**
 *
 * Available object definition backend options:
 *      @see DataModel_Backend_MySQL_Config
 *
 * Available property definition backend options:
 *		column_type:
 *				string, default: null (auto)
 *				Force MySQL column type and options definition
 *		key:
 *				string, default: null
 *				Key on column(s)
 *		key_type:
 *				string: default: null
 *				Type of the key. Options: INDEX(default), UNIQUE
 *
 * @copyright Copyright (c) 2014 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Backend
 */
namespace Jet;

class DataModel_Backend_Oracle extends DataModel_Backend_Abstract {
	const PRIMARY_KEY_NAME = 'PRIMARY';
	const ROW_NUM_KEY = 'RN____';

	/**
	 * @var DataModel_Backend_Oracle_Config
	 */
	protected $config;

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
	 * @var array
	 */
	protected static $valid_key_types = array(
		DataModel::KEY_TYPE_PRIMARY,
		DataModel::KEY_TYPE_INDEX,
		DataModel::KEY_TYPE_UNIQUE,
		//DataModel::KEY_TYPE_FULLTEXT
	);


	/**
	 *
	 */
	public function initialize() {
		$this->_db_read = Db::get( $this->config->getConnectionRead() );
		$this->_db_read->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, true);
		$this->_db_write = Db::get( $this->config->getConnectionWrite() );
	}

	/**
	 * @param DataModel $data_model
	 * @param string|null $force_table_name (optional)
	 *
	 * @return string
	 */
	public function helper_getCreateCommand( DataModel $data_model, $force_table_name=null ) {

		$data_model_definition = $data_model->getDataModelDefinition();


		$_columns = array();

		$table_name = $this->_getTableName( $data_model_definition );

		$table_name = $force_table_name ? $force_table_name : $table_name;


		foreach( $data_model_definition->getProperties() as $name=>$property ) {
			if(
				$property->getIsDataModel()
			) {
				continue;
			}
			$name = $this->_getColumnName($name);

			$_columns[] = JET_TAB.'\''.$name.'\' '.$this->_getSQLType( $data_model, $property );
		}

		$create_index_query = array();

		$keys = array();
		foreach($data_model_definition->getKeys() as $key_name=>$key) {

			$columns = implode('', $this->_getColumnName($key->getPropertyNames()) );

			switch( $key->getType() ) {
				case DataModel::KEY_TYPE_PRIMARY:
					$keys[$key_name] = JET_EOL.',CONSTRAINT "'.$this->_getColumnName($key_name).'" PRIMARY KEY ("'.$columns.'")';
				break;
				case DataModel::KEY_TYPE_INDEX:
					$create_index_query[] = JET_EOL.'CREATE INDEX "'.$this->_getColumnName($table_name.'_'.$key_name).'" ON '.$table_name.' ("'.$columns.'")';
				break;
				default:
					$create_index_query[] = JET_EOL.'CREATE '.$key['type'].' INDEX "'.$this->_getColumnName($table_name.'_'.$key_name).'" ON '.$table_name.' ("'.$columns.'")';
				break;
			}
		}

		$q = 'DECLARE'.JET_EOL;
		$q .= 'cnt NUMBER;'.JET_EOL;
		$q .= 'BEGIN'.JET_EOL;
		$q .= 'SELECT count(*) INTO cnt FROM user_tables WHERE table_name = UPPER(\''.$table_name.'\') or table_name = \''.$table_name.'\';'.JET_EOL;
		$q .= 'IF cnt = 0 THEN'.JET_EOL;
		$q .= 'EXECUTE IMMEDIATE \'CREATE TABLE '.$table_name.' ('.JET_EOL;
		$q .= implode(','.JET_EOL, $_columns);
		$q .= implode(JET_EOL, $keys);
		$q .= ')\';'.JET_EOL;

		foreach($create_index_query as $ciq) {
			$q .= 'EXECUTE IMMEDIATE \''.$ciq.'\';'.JET_EOL;
		}
		$q .= 'END IF;'.JET_EOL;
		$q .= 'END;'.JET_EOL;

		return $q;
	}

	/**
	 * @param DataModel $data_model
	 */
	public function helper_create( DataModel $data_model ) {
		$this->_db_write->execCommand( $this->helper_getCreateCommand( $data_model ) );
	}

	/**
	 * @param DataModel $data_model
	 *
	 * @return string
	 */
	public function helper_getDropCommand( DataModel $data_model ) {
		$table_name = $this->_getTableName( $data_model->getDataModelDefinition() );
		$ui_prefix = '_d'.date('YmdHis');

		return 'RENAME TABLE '.$table_name.' TO '.$ui_prefix.$table_name.'';
	}

	/**
	 * @param DataModel $data_model
	 */
	public function helper_drop( DataModel $data_model ) {
		$this->_db_write->execCommand( $this->helper_getDropCommand( $data_model ) );
	}

	/**
	 * @param DataModel $data_model
	 *
	 * @return array
	 */
	public function helper_getUpdateCommand( DataModel $data_model ) {
		//TODO: check it and port it
		$data_model_definition = $data_model->getDataModelDefinition();
		$table_name = $this->_getTableName($data_model_definition);

		$update_prefix = '_UP'.date('YmdHis').'_';
		$exists_cols = $this->_db_write->fetchCol('DESCRIBE '.$table_name);

		$updated_table_name = $update_prefix.$table_name;

		$create_command = $this->helper_getCreateCommand( $data_model, $updated_table_name );


		$properties = $data_model_definition->getProperties();
		$actual_cols = array();
		foreach($properties as $property_name=>$property) {
			if( $property->getIsDataModel() ) {
				continue;
			}
			$actual_cols[$property_name] = $property;
		}

		$common_cols = array_intersect(array_keys($actual_cols), $exists_cols);
		$_new_cols = array_diff( array_keys($actual_cols), $exists_cols );
		$new_cols = new DataModel_RecordData($data_model_definition);
		foreach($_new_cols as $new_col) {
			$new_cols->addItem($properties[$new_col], $properties[$new_col]->getDefaultValue( $data_model ));
		}


		$data_migration_command = 'INSERT INTO '.$updated_table_name.'
					("'.implode('","', $common_cols).'")
				SELECT
					"'.implode('","', $common_cols).'"
				FROM '.$table_name.';';

		$update_default_values = '';
		if( ($new_cols = $this->_getRecord($new_cols)) ) {

			foreach($new_cols as $c=>$v) {
				$_new_cols[] = $c.'='.$v;
			}
			$update_default_values = 'UPDATE '.$updated_table_name.' SET '.implode(','.JET_EOL, $_new_cols);
		}


		$rename_command1 = 'RENAME TABLE '.$table_name.' TO '.$update_prefix.'b_'.$table_name.' ;'.JET_EOL;
		$rename_command2 = 'RENAME TABLE '.$updated_table_name.' TO  '.$table_name.'; ';

		$update_command = array();
		$update_command[] = $create_command;
		$update_command[] = $data_migration_command;
		if($update_default_values) {
			$update_command[] = $update_default_values;
		}
		$update_command[] = $rename_command1;
		$update_command[] = $rename_command2;

		return $update_command;
	}

	/**
	 * @param DataModel $data_model
	 *
	 * @throws \Exception|Exception
	 */
	public function helper_update( DataModel $data_model ) {
		$this->transactionStart();
		try {
			foreach($this->helper_getUpdateCommand( $data_model ) as $q) {
				$this->_db_write->execCommand( $q );
			}
		} catch (Exception $e) {
			$this->transactionRollback();
			throw $e;
		}
		$this->transactionCommit();
	}


	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	public function getBackendSelectQuery( DataModel_Query $query ) {

		$limit_part = $this->_getSQLQueryLimitPart($query);

		$q = '';

		if($limit_part) {
			$q = 'SELECT * FROM ('.JET_EOL;
		}

		$q .= 'SELECT'.JET_EOL
			.JET_TAB.$this->_getSQLQuerySelectPart($query);
		if($limit_part) {
			$q .= ',ROWNUM AS '.static::ROW_NUM_KEY;
		}
		$q .= JET_EOL.'FROM'.JET_EOL
			.JET_TAB.$this->_getSQLQueryTableName($query)
			.$this->_getSQLQueryJoinPart($query)

			.$this->_getSQLqueryWherePart($query->getWhere())
			.$this->_getSQLQueryGroupPart($query)
			.$this->_getSQLQueryHavingPart($query->getHaving())
			.$this->_getSQLQueryOrderByPart($query)
			.$limit_part;

		return $q;

	}


	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	public function getBackendCountQuery( DataModel_Query $query ) {
		return 'SELECT count(*) FROM'.JET_EOL.JET_TAB
			.$this->_getSQLQueryTableName($query)
			.$this->_getSQLQueryJoinPart($query)
			.$this->_getSQLqueryWherePart($query->getWhere())
			.$this->_getSQLQueryGroupPart($query)
			.$this->_getSQLQueryHavingPart($query->getHaving());
	}

	/**
	 * @param DataModel_RecordData $record
	 *
	 *
	 * @return string
	 */
	public function getBackendInsertQuery( DataModel_RecordData $record ) {

		$data_model_definition = $record->getDataModelDefinition();

		$table_name = $this->_getTableName($data_model_definition);

		$rec = $this->_getRecord($record);

		$columns = implode(','.JET_EOL, array_keys($rec));
		$values = implode(','.JET_EOL, $rec);

		return 'INSERT INTO '.$table_name.' ('.$columns.') VALUES ('.$values.')';
	}

	/**
	 * @param DataModel_RecordData $record
	 * @param DataModel_Query $where
	 *
	 * @return string
	 */
	public function getBackendUpdateQuery( DataModel_RecordData $record, DataModel_Query $where ) {
		$data_model_definition = $record->getDataModelDefinition();
		$table_name = $this->_getTableName($data_model_definition);

		$set = array();

		foreach($this->_getRecord($record) as $k=>$v) {
			$set[] = $k.'='.$v;

		}

		$set = implode(','.JET_EOL, $set);

		$where = $this->_getSQLqueryWherePart($where->getWhere());

		return 'UPDATE '.$table_name.' SET '.JET_EOL.$set.$where;

	}

	/**
	 * @param DataModel_Query $where
	 *
	 * @return string
	 */
	public function getBackendDeleteQuery( DataModel_Query $where ) {
		$table_name = $this->_getTableName($where->getMainDataModelDefinition());
		return 'DELETE FROM '.$table_name.$this->_getSQLqueryWherePart($where->getWhere());
	}

	/**
	 * @param DataModel_RecordData $record
	 *
	 * @return mixed
	 */
	public function save( DataModel_RecordData $record ) {
		$this->_db_write->execCommand( $this->getBackendInsertQuery($record), $this->_getRecordValues($record) );

		return $this->_db_write;
	}

	/**
	 * @param DataModel_RecordData $record
	 * @param DataModel_Query $where
	 *
	 * @return int
	 */
	public function update( DataModel_RecordData $record, DataModel_Query $where) {
		return $this->_db_write->execCommand( $this->getBackendUpdateQuery($record, $where), $this->_getRecordValues($record) );
	}

	/**
	 * @param DataModel_Query $where
	 *
	 * @return int
	 */
	public function delete( DataModel_Query $where ) {
		return $this->_db_write->execCommand( $this->getBackendDeleteQuery($where) );
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return int
	 */
	public function getCount( DataModel_Query $query ) {
		return (int)$this->_db_read->fetchOne( $this->getBackendCountQuery($query) );
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	public function fetchAll( DataModel_Query $query ) {
		return $this->_fetch($query, 'fetchAll');
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	public function fetchAssoc( DataModel_Query $query ) {
		return $this->_fetch($query, 'fetchAssoc');
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	public function fetchPairs( DataModel_Query $query ) {
		return $this->_fetch($query, 'fetchPairs');
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	public function fetchRow( DataModel_Query $query ) {
		return $this->_fetch($query, 'fetchRow');
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return mixed
	 */
	public function fetchOne( DataModel_Query $query ) {
		return $this->_fetch($query, 'fetchOne');
	}

	/**
	 * @param DataModel_Query $query
	 * @param string $fetch_method
	 *
	 * @return mixed
	 */
	protected function _fetch( DataModel_Query $query, $fetch_method ) {



		$data = $this->_db_read->$fetch_method(
			$this->getBackendSelectQuery( $query )
		);

		if(!is_array($data)) {
			return $data;
		}

		$fetch_row = ($fetch_method=='fetchRow');

		if($fetch_row) {
			$data = [$data];
		}

		foreach($data as $i=>$d) {
			if(isset($data[$i][static::ROW_NUM_KEY])) {
				unset($data[$i][static::ROW_NUM_KEY]);
			}

			foreach($query->getSelect() as $item) {
				/**
				 * @var DataModel_Query_Select_Item $item
				 * @var DataModel_Definition_Property_Abstract $property
				 */
				$property = $item->getItem();

				if( ! ($property instanceof DataModel_Definition_Property_Abstract) ) {
					continue;
				}

				$select_as = $item->getSelectAs();
				$key = strtoupper($this->_getColumnName($select_as));
				$_d = $data[$i][$key];
				unset( $data[$i][$key] );

				if($property->getIsArray()) {
					$data[$i][$select_as] = $this->unserialize( $_d );
				} else {
					$data[$i][$select_as] = $_d;
				}

				if(
					$property instanceof DataModel_Definition_Property_DateTime ||
					$property instanceof DataModel_Definition_Property_Date
				) {
					$data[$i][$select_as] = DateTime::createFromFormat('d#M#y H#i#s*A', $data[$i][$select_as]);
				}

				$property->checkValueType( $data[$i][$select_as] );

			}
		}

		if($fetch_row) {
			return $data[0];
		}

		return $data;
	}

	/**
	 *
	 */
	public function transactionStart() {
		$this->_db_write->beginTransaction();
	}

	/**
	 *
	 */
	public function transactionCommit() {
		$this->_db_write->commit();
	}

	/**
	 *
	 */
	public function transactionRollback() {
		$this->_db_write->rollBack();
	}


	/**
	 *
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	protected function _getSQLQuerySelectPart( DataModel_Query $query ) {
		$columns_qp = array();


		$mapper = function(DataModel_Definition_Property_Abstract $property) {
			return $this->_getColumnName($property);
		};

		foreach( $query->getSelect() as $item ) {
			/**
			 * @var DataModel_Query_Select_Item $item
			 */
			$property = $item->getItem();
			$select_as = $this->_getColumnName($item->getSelectAs());

			if($property instanceof DataModel_Definition_Property_Abstract) {
				/**
				 * @var DataModel_Definition_Property_Abstract $property
				 */
				$columns_qp[] = $this->_getColumnName($property).' AS '.$select_as;

				continue;
			}

			if($property instanceof DataModel_Query_Select_Item_BackendFunctionCall) {

				/**
				 * @var DataModel_Query_Select_Item_BackendFunctionCall $property
				 */

				$backend_function_call = $property->toString( $mapper );

				$columns_qp[] = $backend_function_call.' AS '.$select_as;
				continue;
			}

		}

		return implode(','.JET_EOL.JET_TAB, $columns_qp);
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	protected function _getSQLQueryTableName( DataModel_Query $query ) {
		$main_model_definition = $query->getMainDataModelDefinition();
		return $this->_getTableName( $main_model_definition );

	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @throws DataModel_Backend_Exception
	 * @return string
	 */
	protected  function _getSQLQueryJoinPart( DataModel_Query $query ) {
		$join_qp = '';

		foreach($query->getRelations() as $relation) {


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
						'Oracle backend: unknown join type \''.$relation->getJoinType().'\'',
						DataModel_Backend_Exception::CODE_BACKEND_ERROR
					);
				break;
			}

			$j = array();
			$join_by_properties = $relation->getJoinBy();


			foreach( $join_by_properties as $join_by_property ) {
				/**
				 * @var DataModel_Definition_Relation_JoinBy_Item $join_by_property
				 */
				$related_value = $join_by_property->getThisPropertyOrValue();

				if($related_value instanceof DataModel_Definition_Property_Abstract) {
					$related_value = $this->_getColumnName($related_value);
				} else {
					$related_value = $this->_db_read->quote($related_value);
				}

				$j[] = JET_TAB.JET_TAB.JET_TAB.$this->_getColumnName($join_by_property->getRelatedProperty()).' = '.$related_value;

			}


			$join_qp .= implode(' AND '.JET_EOL, $j);
		}

		return $join_qp;
	}


	/**
	 * @param DataModel_Query_Where $query
	 *
	 * @param int $level (optional)
	 *
	 * @return string
	 */
	protected function _getSQLQueryWherePart( DataModel_Query_Where $query=null, $level=0 ) {
		if(!$query) {
			return '';
		}
		$res = '';

		$next_level = $level+1;
		$tab = str_repeat(JET_TAB, $next_level);

		foreach( $query as $qp ) {
			if( $qp instanceof DataModel_Query_Where ) {
				/**
				 * @var DataModel_Query_Where $qp
				 */
				$res .= $tab.'('.JET_EOL.$this->_getSQLqueryWherePart($qp, $next_level).' '.JET_EOL.JET_TAB.')';
				continue;
			}

			if($qp===DataModel_Query::L_O_AND || $qp===DataModel_Query::L_O_OR) {
				/**
				 * @var string $qp
				 */
				$res .= JET_EOL.$tab.$qp.JET_EOL.' ';
				continue;
			}

			/**
			 * @var DataModel_Query_Where_Expression $qp
			 */

			/**
			 * @var DataModel_Definition_Property_Abstract $prop
			 */
			$prop = $qp->getProperty();

			$property_name = $this->_getColumnName( $prop );


			$res .= $tab.$this->_getSQLQueryWherePart_handleExpression(
				$property_name,
				$qp->getOperator(),
				$qp->getValue()
			);

		}

		if($res && !$level) {
			$res = JET_EOL.'WHERE'.JET_EOL.$res.JET_EOL;
		}

		return $res;
	}

	/**
	 * @param DataModel_Query_Having $query
	 *
	 * @param int $level (optional)
	 *
	 * @return string
	 */
	protected function _getSQLQueryHavingPart( DataModel_Query_Having $query=null, $level=0 ) {
		if(!$query) {
			return '';
		}
		$res = '';

		$next_level = $level+1;
		$tab = str_repeat(JET_TAB, $next_level*strlen(JET_TAB));

		foreach( $query as $qp ) {
			if( $qp instanceof DataModel_Query_Having ) {
				/**
				 * @var DataModel_Query_Having $qp
				 */
				$res .= $tab.'('.JET_EOL.$this->_getSQLQueryHavingPart($qp, $next_level ).' '.JET_EOL.JET_TAB.')';
				continue;
			}

			if($qp===DataModel_Query::L_O_AND || $qp===DataModel_Query::L_O_OR) {
				/**
				 * @var string $qp
				 */
				$res .= JET_EOL.$tab.$qp.JET_EOL.' ';
				continue;
			}

			/**
			 * @var DataModel_Query_Having_Expression $qp
			 */

			/**
			 * @var DataModel_Definition_Property_Abstract $prop
			 */
			$item = $this->_getColumnName($qp->getProperty()->getSelectAs());


			$res .= $tab.$this->_getSQLQueryWherePart_handleExpression(
				$item,
				$qp->getOperator(),
				$qp->getValue()
			);
		}

		if($res && !$level ) {
			$res = JET_EOL.'HAVING'.JET_EOL.$res.JET_EOL;
		}

		return $res;
	}

	/**
	 * @param $item
	 * @param $operator
	 * @param $value
	 *
	 * @return string
	 */
	protected function _getSQLQueryWherePart_handleExpression($item, $operator, $value) {
		$res = '';

		if(is_array($value)) {
			$sq = array();

			/**
			 * @var array $value
			 */
			foreach($value as $v) {

				$sq[] = JET_TAB.JET_TAB.$item.$this->_getSQLQueryWherePart_handleOperator( $operator, $v );
			}

			$res .= '('.JET_EOL.implode(' OR'.JET_EOL, $sq).JET_EOL.JET_TAB.') ';
		} else {
			$res .= $item.$this->_getSQLQueryWherePart_handleOperator($operator, $value);

		}

		return $res;

	}


	/**
	 * @param string $operator
	 * @param mixed $value
	 *
	 * @throws DataModel_Backend_Exception
	 * @return string
	 */
	protected function _getSQLQueryWherePart_handleOperator($operator, $value) {
		$value = $this->_getValue($value);

		$res = '';

		switch($operator) {
			case DataModel_Query::O_EQUAL:
				$res .='='.$value.' ';
				break;
			case DataModel_Query::O_NOT_EQUAL:
				$res .='<>'.$value.' ';
				break;
			case DataModel_Query::O_LIKE:
				$res .=' LIKE'.$value.' ';
				break;
			case DataModel_Query::O_NOT_LIKE:
				$res .=' NOT LIKE'.$value.' ';
				break;
            case DataModel_Query::O_GREATER_THAN:
	            $res .='>'.$value.' ';
                break;
            case DataModel_Query::O_LESS_THAN:
	            $res .='<'.$value.' ';
                break;
            case DataModel_Query::O_GREATER_THAN_OR_EQUAL:
	            $res .='>='.$value.' ';
                break;
            case DataModel_Query::O_LESS_THAN_OR_EQUAL:
	            $res .='<='.$value.' ';
                break;

			default:
				throw new DataModel_Backend_Exception(
					'Unknown operator '.$operator.'! ',
					DataModel_Backend_Exception::CODE_BACKEND_ERROR
				);



		}

		return $res;
	}


	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	protected function _getSQLQueryGroupPart( DataModel_Query $query=null ) {
		$group_by = $query->getGroupBy();
		if( !$group_by ) {
			return '';
		}

		$group_by_qp = array();

		foreach($group_by as $val) {
			/**
			 * @var DataModel_Query_Select_Item $val
			 */
			if($val instanceof DataModel_Definition_Property_Abstract) {
				/**
				 * @var DataModel_Definition_Property_Abstract $val
				 */
				$val = $this->_getColumnName($val);
			} else
			if($val instanceof DataModel_Query_Select_Item) {
				$val = $this->_getColumnName($val->getSelectAs());
			}

			$group_by_qp[] = $val;
		}

		$group_by_qp = JET_EOL.'GROUP BY'.JET_EOL.JET_TAB.implode(','.JET_EOL.JET_TAB, $group_by_qp).JET_EOL;

		return $group_by_qp;
	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	protected function _getSQLQueryOrderByPart( DataModel_Query $query ) {
		$order_by = $query->getOrderBy();

		if(!$order_by) {
			return '';
		}

		$order_qp = array();

		foreach($order_by  as $ob ) {
			/**
			 * @var DataModel_Query_OrderBy_Item $ob
			 */
			$item = $ob->getItem();
			if($item instanceof DataModel_Definition_Property_Abstract) {
				$item = $this->_getColumnName($item);
			} else
			if($item instanceof DataModel_Query_Select_Item) {
				$item = $this->_getColumnName($item->getSelectAs());
			}
			$order_by_desc = $ob->getDesc();


			/**
			 * @var string $item
			 */
			if($order_by_desc) {
				$order_qp[] = $item.' DESC';
			} else {
				$order_qp[] = $item.' ASC';
			}
		}

		if(!$order_qp) {
			return '';
		}

		return JET_EOL.'ORDER BY'.JET_EOL.JET_TAB.implode(','.JET_EOL.JET_TAB, $order_qp).JET_EOL;

	}

	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	protected function _getSQLQueryLimitPart( DataModel_Query $query ) {
		$limit_qp = '';

		$offset = (int)$query->getOffset();
		$limit = (int)$query->getLimit();

		if($limit) {
			if($offset) {
				$limit = $offset+$limit;
				$limit_qp = JET_EOL.') WHERE '.static::ROW_NUM_KEY.' >= '.$offset.' AND '.static::ROW_NUM_KEY.' < '.$limit;
			} else {
				$limit_qp = JET_EOL.') WHERE '.static::ROW_NUM_KEY.' <= '.$limit;
			}
		}

		return $limit_qp;
	}

	/**
	 * @param DataModel $data_model
	 * @param DataModel_Definition_Property_Abstract $column
	 *
	 * @throws DataModel_Exception
	 * @throws DataModel_Backend_Exception
	 * @return string
	 */
	protected function _getSQLType( DataModel $data_model, DataModel_Definition_Property_Abstract $column ) {
		$backend_options = $column->getBackendOptions( 'Oracle' );

		$name = $column->getName();


		if( isset($backend_options['column_type']) && $backend_options['column_type'] ) {
			return $backend_options['column_type'];
		}

		switch($column->getType()) {
			case DataModel::TYPE_ID:

					$max_len = (int)$data_model->getEmptyIDInstance()->getMaxLength();

					return 'varchar('.$max_len.') NOT NULL';
				break;
			case DataModel::TYPE_STRING:
				$max_len = (int)$column->getMaxLen();

				if($max_len<=4000) {
					if($column->getIsID()) {
						return 'varchar('.((int)$max_len).') NOT NULL';
					} else {
						return 'varchar('.((int)$max_len).')';
					}
				}


				return 'CLOB';
				break;
			case DataModel::TYPE_BOOL:
				return 'char(1)';
				break;
			case DataModel::TYPE_INT:
				return 'number(12)';
				break;
			case DataModel::TYPE_FLOAT:
				return 'float';
				break;
			case DataModel::TYPE_LOCALE:
				return 'varchar(20) NOT NULL';
				break;
			case DataModel::TYPE_DATE:
				return ' TIMESTAMP WITH TIME ZONE';
				break;
			case DataModel::TYPE_DATE_TIME:
				return ' TIMESTAMP WITH TIME ZONE';
				break;
			case DataModel::TYPE_ARRAY:
				return 'CLOB';
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
	 * @param DataModel_RecordData $record
	 *
	 * @return array
	 */
	protected function _getRecord( DataModel_RecordData $record ) {
		$_record = array();


		foreach($record as $item) {
			/**
			 * @var DataModel_RecordData_Item $item
			 */

			$definition = $item->getPropertyDefinition();
			$property_name = $definition->getName();
			$column_name = $this->_getColumnName($property_name);


			$value = ':'.$column_name;

			if(
				$definition instanceof DataModel_Definition_Property_Date || $definition instanceof DataModel_Definition_Property_DateTime
			) {
				$value = 'TO_TIMESTAMP_TZ('.$value.', \'YYYY-MM-DD"T"HH24:MI:SSTZHTZM\')';
			}

			$_record['\''.$column_name.'\''] = $value;
		}

		return $_record;
	}

	/**
	 * @param DataModel_RecordData $record
	 *
	 * @return array
	 */
	protected function _getRecordValues( DataModel_RecordData $record ) {
		$_record = array();


		foreach($record as $item) {
			/**
			 * @var DataModel_RecordData_Item $item
			 */

			$property_name = $item->getPropertyDefinition()->getName();

			$value = $item->getValue();

			if( is_bool($value) ) {
				$value = $value ? 1 : 0;
			} else if(is_array($value)) {
				$value = $this->serialize($value);
			} else if(is_object($value)) {
				$value = (string)$value;
			}


			$_record[$this->_getColumnName($property_name)] = $value;
		}

		return $_record;
	}


	/**
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	protected function _getValue( $value ) {
		if($value===null) {
			return 'null';
		}

		if(is_bool($value)) {
			return $value ? 1 : 0;
		}

		if(is_int($value)) {
			return (int)$value;
		}

		if(is_float($value)) {
			return (float)$value;
		}

		if(is_array($value)) {
			return serialize($value);
		}

		if($value instanceof DateTime) {
			return 'TO_TIMESTAMP_TZ(\''.$value.'\', \'YYYY-MM-DD"T"HH24:MI:SSTZHTZM\')';
		}

		return $this->_db_read->quote( $value );
	}


	/**
	 * @param DataModel_Definition_Model_Abstract $model_definition
	 *
	 * @return string
	 */
	protected function _getTableName(DataModel_Definition_Model_Abstract $model_definition) {
		$table_name = strtolower($model_definition->getDatabaseTableName());

		return $this->_OracleWorkaround($table_name);
	}


	/**
	 * Oracle has some limits ... :-(
	 *
	 * @param array|DataModel_Definition_Property_Abstract|string $column
	 * @return array|string
	 */
	protected function _getColumnName( $column ) {
		if($column instanceof DataModel_Definition_Property_Abstract) {
			$property_table_name = $this->_getTableName( $column->getDataModelDefinition() );

			return $property_table_name.'."'.$this->_getColumnName($column->getName()).'"';
		}


		if(is_array($column)) {
			foreach($column as $i=>$c) {
				$column[$i] = $this->_getColumnName($c);
			}
			return $column;
		}

		return $this->_OracleWorkaround($column);
	}

	/**
	 * @param $name
	 *
	 * @return string
	 */
	protected function _OracleWorkaround( $name ) {
		if(strlen($name)>30) {
			//... because of Oracle :-(
			return substr($name, 0, 20).substr(md5($name), 0, 9);
		}

		return $name;

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