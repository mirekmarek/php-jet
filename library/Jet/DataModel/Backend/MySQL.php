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
 *		auto_increment:
 *				bool, default: false
 *				Use auto increment for ID
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

class DataModel_Backend_MySQL extends DataModel_Backend_Abstract {

	/**
	 * @var DataModel_Backend_MySQL_Config
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
	 * @var int
	 */
	protected $_last_result_count = null;

	/**
	 * @var array
	 */
	protected static $valid_key_types = [
		DataModel::KEY_TYPE_PRIMARY,
		DataModel::KEY_TYPE_INDEX,
		DataModel::KEY_TYPE_UNIQUE,
		//DataModel::KEY_TYPE_FULLTEXT
	];


	/**
	 *
	 */
	public function initialize() {
		$this->_db_read = Db::get( $this->config->getConnectionRead() );
		$this->_db_write = Db::get( $this->config->getConnectionWrite() );
	}

	/**
	 * @param DataModel_Interface $data_model
	 * @param string|null $force_table_name (optional)
	 *
	 * @return string
	 */
	public function helper_getCreateCommand( DataModel_Interface $data_model, $force_table_name=null ) {

		$data_model_definition = $data_model->getDataModelDefinition();
		$options = [];

		$options['ENGINE'] = $this->config->getEngine();
		$options['DEFAULT CHARSET'] = $this->config->getDefaultCharset();
		$options['COLLATE'] = $this->config->getCollate();

		$_options = [];

		foreach($options as $o=>$v) {
			$_options[] = $o.'='.$v;
		}

		$_options = implode(' ', $_options);


		$_columns = [];

		foreach( $data_model_definition->getProperties() as $name=>$property ) {
			if( !$property->getCanBeTableField() ) {
				continue;
			}

			$_columns[] = JET_TAB.'`'.$name.'` '.$this->_getSQLType( $data_model, $property );
		}

		$_keys = [];
		foreach( $data_model_definition->getKeys() as $key_name=>$key ) {

			switch( $key->getType() ) {
				case DataModel::KEY_TYPE_PRIMARY:
					$_keys[$key_name] = JET_EOL.JET_TAB.',PRIMARY KEY (`'.implode('`, `', $key->getPropertyNames()).'`)';
				break;
				case DataModel::KEY_TYPE_INDEX:
					$_keys[$key_name] = JET_EOL.JET_TAB.',KEY `'.$key_name.'`  (`'.implode('`, `', $key->getPropertyNames()).'`)';
				break;
				default:
					$_keys[$key_name] = JET_EOL.JET_TAB.','.$key->getType().' KEY `'.$key_name.'`  (`'.implode('`, `', $key->getPropertyNames()).'`)';
				break;
			}
		}

		$table_name = $this->_getTableName( $data_model_definition );

		$table_name = $force_table_name ? $force_table_name : $table_name;

		$q = 'CREATE TABLE IF NOT EXISTS `'.$table_name.'` ('.JET_EOL;
		$q .= implode(','.JET_EOL, $_columns);
		$q .= implode('', $_keys);
		$q .= JET_EOL.') '.$_options.';'.JET_EOL.JET_EOL;

		return $q;
	}

	/**
	 * @param DataModel_Interface $data_model
	 */
	public function helper_create( DataModel_Interface $data_model ) {
		$this->_db_write->execCommand( $this->helper_getCreateCommand( $data_model ) );
	}

	/**
	 * @param DataModel_Interface $data_model
	 *
	 * @return string
	 */
	public function helper_getDropCommand( DataModel_Interface $data_model ) {
		$table_name = $this->_getTableName( $data_model->getDataModelDefinition() );
		$ui_prefix = '_d'.date('YmdHis');

		return 'RENAME TABLE `'.$table_name.'` TO `'.$ui_prefix.$table_name.'`';
	}

	/**
	 * @param DataModel_Interface $data_model
	 */
	public function helper_drop( DataModel_Interface $data_model ) {
		$this->_db_write->execCommand( $this->helper_getDropCommand( $data_model ) );
	}

	/**
	 * @param DataModel_Interface $data_model
	 *
	 * @return array
	 */
	public function helper_getUpdateCommand( DataModel_Interface $data_model ) {
		$data_model_definition = $data_model->getDataModelDefinition();
		$table_name = $this->_getTableName($data_model_definition);

		$update_prefix = '_UP'.date('YmdHis').'_';
		$exists_cols = $this->_db_write->fetchCol('DESCRIBE `'.$table_name.'`');

		$updated_table_name = $update_prefix.$table_name;

		$create_command = $this->helper_getCreateCommand( $data_model, $updated_table_name );


		$properties = $data_model_definition->getProperties();
		$actual_cols = [];
		foreach($properties as $property_name=>$property) {
			/**
			 * @var DataModel_Definition_Property_Abstract $property
			 */
			if( !$property->getCanBeTableField() ) {
				continue;
			}
			$actual_cols[$property_name] = $property;
		}

		$common_cols = array_intersect(array_keys($actual_cols), $exists_cols);
		$_new_cols = array_diff( array_keys($actual_cols), $exists_cols );
		$new_cols = new DataModel_RecordData($data_model_definition);
		foreach($_new_cols as $new_col) {
			$new_cols->addItem($properties[$new_col], $properties[$new_col]->getDefaultValue());
		}
		$new_cols = $this->_getRecord($new_cols);

		$data_migration_command = 'INSERT INTO `'.$updated_table_name.'` (`'.implode('`,`', $common_cols).'`) SELECT `'.implode('`,`', $common_cols).'` FROM `'.$table_name.'`;';

		$update_default_values = '';
		if($new_cols) {
			$_new_cols = [];
			foreach($new_cols as $c=>$v) {
				$_new_cols[] = '`'.$c.'`='.$this->_getValue($v, true);
			}
			$update_default_values = 'UPDATE `'.$updated_table_name.'` SET '.implode(', ', $_new_cols);
		}


		$rename_command1 = 'RENAME TABLE `'.$table_name.'` TO `'.$update_prefix.'b_'.$table_name.'`;';
		$rename_command2 = 'RENAME TABLE `'.$updated_table_name.'` TO  `'.$table_name.'`;';

		$update_command = [];
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
	 * @param DataModel_Interface $data_model
	 *
	 * @throws \Exception|Exception
	 */
	public function helper_update( DataModel_Interface $data_model ) {
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
		$select = 'SELECT';

		if($this->data_pagination_mode) {
			$select .= ' SQL_CALC_FOUND_ROWS ';
		}

		return $select.JET_EOL
			.JET_TAB.$this->_getSQLQuerySelectPart($query).JET_EOL
			.'FROM'.JET_EOL
			.JET_TAB.$this->_getSQLQueryTableName($query)
			.$this->_getSQLQueryJoinPart($query)

			.$this->_getSqlQueryWherePart($query->getWhere())
			.$this->_getSqlQueryGroupPart($query)
			.$this->_getSqlQueryHavingPart($query->getHaving())
			.$this->_getSqlQueryOrderByPart($query)
			.$this->_getSqlQueryLimitPart($query);

	}


	/**
	 * @param DataModel_Query $query
	 *
	 * @return string
	 */
	public function getBackendCountQuery( DataModel_Query $query ) {
		return 'SELECT count(*) FROM'.JET_EOL
			.JET_TAB.$this->_getSQLQueryTableName($query)
			.$this->_getSQLQueryJoinPart($query)
			.$this->_getSqlQueryWherePart($query->getWhere())
			.$this->_getSqlQueryGroupPart($query)
			.$this->_getSqlQueryHavingPart($query->getHaving());
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

		$set = [];

		foreach($this->_getRecord($record) as $k=>$v) {
			if($v===null) {
				$set[] = '`'.$k.'`=null';
			} else
			if(is_string($v)) {
				$set[] = '`'.$k.'`='.$this->_db_write->quote($v);
			} else {
				$set[] = '`'.$k.'`='.$v;
			}
		}

		$set = implode(','.JET_EOL, $set);


		return 'INSERT INTO `'.$table_name.'` SET '.JET_EOL.$set;
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

		$set = [];

		foreach($this->_getRecord($record) as $k=>$v) {
			if($v===null) {
				$set[] = '`'.$k.'`=null';
			} else
			if(is_string($v)) {
				$set[] = '`'.$k.'`='.$this->_db_write->quote($v);
			} else {
				$set[] = '`'.$k.'`='.$v;
			}
		}

		$set = implode(','.JET_EOL, $set);

		$where = $this->_getSqlQueryWherePart($where->getWhere());

		return 'UPDATE `'.$table_name.'` SET '.JET_EOL.$set.$where;

	}

	/**
	 * @param DataModel_Query $where
	 *
	 * @return string
	 */
	public function getBackendDeleteQuery( DataModel_Query $where ) {
		$table_name = $this->_getTableName($where->getMainDataModelDefinition());
		return 'DELETE FROM `'.$table_name.'`'.$this->_getSqlQueryWherePart($where->getWhere());
	}

	/**
	 * @param DataModel_RecordData $record
	 *
	 * @return mixed
	 */
	public function save( DataModel_RecordData $record ) {
		$this->_db_write->execCommand( $this->getBackendInsertQuery($record) );

		return $this->_db_write->lastInsertId();
	}

	/**
	 * @param DataModel_RecordData $record
	 * @param DataModel_Query $where
	 *
	 * @return int
	 */
	public function update( DataModel_RecordData $record, DataModel_Query $where) {

		return $this->_db_write->execCommand( $this->getBackendUpdateQuery($record, $where) );
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

		if($this->_last_result_count!==null) {
			return $this->_last_result_count;
		}

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
	 *
	 * @return mixed
	 */
	public function fetchCol( DataModel_Query $query ) {
		$data = $this->_db_read->fetchCol(
			$this->getBackendSelectQuery( $query )
		);

		if(!is_array($data)) {
			return $data;
		}


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

				if($property->getMustBeSerializedBeforeStore()) {
					$data[$i] = $this->unserialize( $data[$i] );
				}

				$property->checkValueType( $data[$i] );

				break;
			}
		}

		return $data;
	}


	/**
	 * @param DataModel_Query $query
	 * @param string $fetch_method
	 *
	 * @return mixed
	 */
	protected function _fetch( DataModel_Query $query, $fetch_method ) {

		$this->_last_result_count = null;
		$data = $this->_db_read->$fetch_method(
			$this->getBackendSelectQuery( $query )
		);

		if(!is_array($data)) {
			return $data;
		}

		if($this->data_pagination_mode) {
			$this->_last_result_count = (int)$this->_db_read->fetchOne( 'SELECT FOUND_ROWS();' );

		}

		return $this->validateResultData( $query, $fetch_method, $data );
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
		$columns_qp = [];


		$mapper = function(DataModel_Definition_Property_Abstract $property) {
			return $this->_getColumnName($property);
		};

		foreach( $query->getSelect() as $item ) {
			/**
			 * @var DataModel_Query_Select_Item $item
			 */
			$property = $item->getItem();
			$select_as = $item->getSelectAs();

			if($property instanceof DataModel_Definition_Property_Abstract) {
				/**
				 * @var DataModel_Definition_Property_Abstract $property
				 */
				$columns_qp[] = $this->_getColumnName($property).' AS `'.$select_as.'`';

				continue;
			}

			if($property instanceof DataModel_Query_Select_Item_BackendFunctionCall) {

				/**
				 * @var DataModel_Query_Select_Item_BackendFunctionCall $property
				 */

				$backend_function_call = $property->toString( $mapper );

				$columns_qp[] = $backend_function_call.' AS `'.$select_as.'`';
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
		return '`'.$this->_getTableName( $main_model_definition ).'`';

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
					$join_qp .= JET_EOL.JET_TAB.JET_TAB.'JOIN `'.$r_table_name.'` ON'.JET_EOL;
				break;
				case DataModel_Query::JOIN_TYPE_LEFT_OUTER_JOIN:
					$join_qp .= JET_EOL.JET_TAB.JET_TAB.'LEFT OUTER JOIN `'.$r_table_name.'` ON'.JET_EOL;
				break;
				default:
					throw new DataModel_Backend_Exception(
						'MySQL backend: unknown join type \''.$relation->getJoinType().'\'',
						DataModel_Backend_Exception::CODE_BACKEND_ERROR
					);
				break;
			}

			$j = [];
			$join_by_properties = $relation->getJoinBy();


			foreach( $join_by_properties as $join_by_property ) {
				$related_value = $join_by_property->getThisPropertyOrValue( $query );
				if($related_value===null) {
					continue;
				}

				if($related_value instanceof DataModel_Definition_Property_Abstract) {
					$related_value = $this->_getColumnName($related_value);
				} else {
					$related_value = $this->_db_read->quote($related_value);
				}

				$j[] = JET_TAB.JET_TAB.JET_TAB.$this->_getColumnName($join_by_property->getRelatedProperty()).' = '.$related_value;

			}


			$join_qp .= implode(' AND'.JET_EOL, $j);
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
	protected function _getSqlQueryWherePart(DataModel_Query_Where $query=null, $level=0 ) {
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
				$res .= $tab.'('.JET_EOL.$this->_getSqlQueryWherePart($qp, $next_level).JET_EOL.JET_TAB.')';
				continue;
			}

			if($qp===DataModel_Query::L_O_AND || $qp===DataModel_Query::L_O_OR) {
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
			 * @var DataModel_Definition_Property_Abstract $prop
			 */
			$prop = $qp->getProperty();

			$table_name = $this->_getTableName( $prop->getDataModelDefinition() );
			$property_name = '`'.$table_name.'`.`'.$prop->getName().'`';


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
	protected function _getSqlQueryHavingPart(DataModel_Query_Having $query=null, $level=0 ) {
		if(!$query) {
			return '';
		}
		$res = '';

		$next_level = $level+1;
		$tab = str_repeat(JET_TAB, $next_level);

		foreach( $query as $qp ) {
			if( $qp instanceof DataModel_Query_Having ) {
				/**
				 * @var DataModel_Query_Having $qp
				 */
				$res .= $tab.'('.JET_EOL.$this->_getSqlQueryHavingPart($qp, $next_level ).JET_EOL.JET_TAB.')';
				continue;
			}

			if($qp===DataModel_Query::L_O_AND || $qp===DataModel_Query::L_O_OR) {
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
			 * @var DataModel_Definition_Property_Abstract $prop
			 */
			$item = $qp->getProperty()->getSelectAs();


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

			$sq = [];

			/**
			 * @var array $value
			 */
			foreach($value as $v) {

				$sq[] = JET_TAB.JET_TAB.$item.$this->_getSQLQueryWherePart_handleOperator( $operator, $v );
			}

			$res .= '('.JET_EOL.implode('OR'.JET_EOL, $sq).JET_EOL.JET_TAB.') ';
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

		$value = $this->_getValue( $value );

		$res = '';

		switch($operator) {
			case DataModel_Query::O_EQUAL:
				$res .='='.$value;
				break;
			case DataModel_Query::O_NOT_EQUAL:
				$res .='<>'.$value;
				break;
			case DataModel_Query::O_LIKE:
				$res .=' LIKE '.$value;
				break;
			case DataModel_Query::O_NOT_LIKE:
				$res .=' NOT LIKE '.$value;
				break;
			case DataModel_Query::O_GREATER_THAN:
				$res .='>'.$value;
				break;
			case DataModel_Query::O_LESS_THAN:
				$res .='<'.$value;
				break;
			case DataModel_Query::O_GREATER_THAN_OR_EQUAL:
				$res .='>='.$value;
				break;
			case DataModel_Query::O_LESS_THAN_OR_EQUAL:
				$res .='<='.$value;
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
	protected function _getSqlQueryGroupPart(DataModel_Query $query=null ) {
		$group_by = $query->getGroupBy();
		if( !$group_by ) {
			return '';
		}

		$group_by_qp = [];

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
				$val = $val->getSelectAs();
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
	protected function _getSqlQueryOrderByPart(DataModel_Query $query ) {
		$order_by = $query->getOrderBy();

		if(!$order_by) {
			return '';
		}

		$order_qp = [];

		foreach($order_by  as $ob ) {
			/**
			 * @var DataModel_Query_OrderBy_Item $ob
			 */
			$item = $ob->getItem();
			if($item instanceof DataModel_Definition_Property_Abstract) {
				$item = $this->_getColumnName($item);
			} else
			if($item instanceof DataModel_Query_Select_Item) {
				$item = $item->getSelectAs();
			}
			$order_by_desc = $ob->getDesc();


			/** @var string $item */
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
	protected function _getSqlQueryLimitPart(DataModel_Query $query ) {
		$limit_qp = '';

		$offset = (int)$query->getOffset();
		$limit = (int)$query->getLimit();

		if($limit) {
			if($offset) {
				$limit_qp = JET_EOL.'LIMIT '.$offset.','.$limit.JET_EOL;
			} else {
				$limit_qp = JET_EOL.'LIMIT '.$limit.JET_EOL;
			}
		}

		return $limit_qp;
	}

	/**
	 * @param DataModel_Interface $data_model
	 * @param DataModel_Definition_Property_Abstract $column
	 *
	 * @throws DataModel_Exception
	 * @throws DataModel_Backend_Exception
	 * @return string
	 */
	protected function _getSQLType( DataModel_Interface $data_model, DataModel_Definition_Property_Abstract $column ) {
		$backend_options = $column->getBackendOptions( 'MySQL' );

		$name = $column->getName();

		$default_value = $column->getDefaultValue();


		if( isset($backend_options['column_type']) && $backend_options['column_type'] ) {
			return $backend_options['column_type'];
		}

		switch($column->getType()) {
			case DataModel::TYPE_ID:

				if( isset($backend_options['auto_increment']) && $backend_options['auto_increment']  ) {
					return 'bigint UNSIGNED auto_increment';
				} else {
					$max_len = (int)$data_model->getEmptyIDInstance()->getMaxLength();

					return 'varchar('.$max_len.') COLLATE utf8_bin NOT NULL DEFAULT \'\'';
				}

				break;
			case DataModel::TYPE_STRING:
				$max_len = (int)$column->getMaxLen();

				if($max_len<=255) {
					if($column->getIsID()) {
						return 'varchar('.((int)$max_len).') COLLATE utf8_bin NOT NULL  DEFAULT \'\'';
					} else {
						return 'varchar('.((int)$max_len).') DEFAULT '.$this->_db_write->quote($default_value);
					}
				}

				if($max_len<=65535) {
					return 'text';
				}

				return 'longtext';
				break;
			case DataModel::TYPE_BOOL:
				return 'tinyint(1) DEFAULT '.($default_value ? 1 : 0 );
				break;
			case DataModel::TYPE_INT:
				return 'int DEFAULT '.(int)$default_value;
				break;
			case DataModel::TYPE_FLOAT:
				return 'float DEFAULT '.(float)$default_value;
				break;
			case DataModel::TYPE_LOCALE:
				return 'varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '.$this->_db_write->quote($default_value);
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
	 * @param DataModel_RecordData $record
	 *
	 * @return array
	 */
	protected function _getRecord( DataModel_RecordData $record ) {
		$_record = [];

		foreach($record as $item) {
			/**
			 * @var DataModel_RecordData_Item $item
			 */

			$_record[$item->getPropertyDefinition()->getName()] = $this->_getValue($item->getValue(), false);
		}

		return $_record;
	}

	/**
	 * @param mixed $value
	 * @param bool $quote (optional, default:true)
	 *
	 * @return mixed
	 */
	protected function _getValue( $value, $quote=true ) {
		if($value instanceof DataModel_Definition_Property_Abstract) {
			return $this->_getColumnName( $value );
		}

		if($value===null) {
			if($quote) {
				return 'NULL';
			} else {
				return null;
			}
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

		if($value instanceof Data_DateTime) {
			$value = $value->format('Y-m-d H:i:s');
		}

		if(is_array($value)) {
			$value = $this->serialize($value);
		}

		if(is_object($value)) {
			$value = (string)$value;
		}

		if(!$quote) {
			return $value;
		}

		return $this->_db_read->quote( $value );
	}


	/**
	 * @param DataModel_Definition_Model_Abstract $model_definition
	 *
	 * @return string
	 */
	protected function _getTableName(DataModel_Definition_Model_Abstract $model_definition) {
		return strtolower($model_definition->getDatabaseTableName());
	}

	/**
	 * @param DataModel_Definition_Property_Abstract $property
	 *
	 * @return string
	 */
	protected function _getColumnName(DataModel_Definition_Property_Abstract $property) {
		$property_table_name = $this->_getTableName( $property->getDataModelDefinition() );
		$property_name = $property->getName();

		return '`'.$property_table_name.'`.`'.$property_name.'`';
	}

}