<?php
/**
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Db
 */
namespace Jet;

class Db_Connection_PDO extends Db_Connection_Abstract {

	/**
	 * Executes query and return affected rows
	 *
	 * @param string $statement
	 *
	 * @return int
	 */
	public function exec( $statement ) {
		Debug_Profiler::SQLQueryStart( $statement );

		$result = parent::exec( $statement );

		Debug_Profiler::SQLQueryEnd();

		return $result;
	}

	/**
	 * @param string $statement
	 * @param int $fetch_method (optional)
	 * @param int|string|object $colno_or_classname_or_object (optional)
	 * @param array $class_constructor_arguments (optional)
	 *
	 * @return \PDOStatement|void
	 */
	public function query( $statement, $fetch_method=0, $colno_or_classname_or_object, $class_constructor_arguments=array() ) {
		Debug_Profiler::SQLQueryStart( $statement );

		$result = parent::query( $statement, $fetch_method, $colno_or_classname_or_object, $class_constructor_arguments );

		Debug_Profiler::SQLQueryEnd();

		return $result;
	}

	/**
	 * Executes commant (INSERT, UPDATE, DELETE or CREATE, ...) and return affected rows
	 *
	 * @param string $query
	 * @param array $query_data
	 *
	 * @return int
	 */
	public function execCommand($query, array $query_data = array()) {
		Debug_Profiler::SQLQueryStart( $query,$query_data );

		$statement = $this->prepare($query);

		foreach( $query_data as $k=>$v ) {
			$type = \PDO::PARAM_STR;
			$len = null;

			if(is_int($v)) {
				$type = \PDO::PARAM_INT;
			} else
			if(is_null($v)) {
				$type = \PDO::PARAM_NULL;
			} else
			if(is_string($v)) {
				$len = strlen($v);
			}

			$statement->bindParam( $k, $query_data[$k], $type, $len );

		}

		$res = $statement->execute();

		Debug_Profiler::SQLQueryEnd();

		return $res;
	}

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 * @return array
	 */
	public function fetchAll($query, array $query_data = array()) {
		$q = $this->prepareQuery($query, $query_data);

		Debug_Profiler::SQLQueryStart( $q, $query_data );
		$stn = parent::query( $q );
		$res = $stn->fetchAll( \PDO::FETCH_ASSOC );

		Debug_Profiler::SQLQueryEnd( count($res) );

		return $res;
	}

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 *
	 * @return array|bool
	 */
	public function fetchRow($query, array $query_data = array()) {
		$res = $this->fetchAll($query, $query_data);

		foreach( $res as $row) {
			//Debug_Profiler::SQLQueryEnd(1);

			return $row;
		}
		return false;
	}

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 * @param string $key_column (optional)
	 *
	 * @return array
	 */
	public function fetchAssoc($query, array $query_data = array(), $key_column = null) {
		$res = $this->fetchAll($query, $query_data);

		$result = array();

		foreach( $res as $row) {
			if($key_column===null) {
				list($key_column) = array_keys($row);
			}
			$key = $row[$key_column];

			$result[$key] = $row;
		}

		return $result;

	}

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 * @param string $column (optional, default: 1st column)
	 *
	 * @return array
	 */
	public function fetchCol($query, array $query_data = array(), $column = null) {
		$res = $this->fetchAll($query, $query_data);

		$result = array();
		foreach( $res as $row) {
			if($column===null) {
				list($column) = array_keys($row);
			}
			$result[] =  $row[$column];
		}

		return $result;
	}

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 * @param string $key_column (optional, default: 1st column)
	 * @param string $value_column (optional, default: 2nd column)
	 *
	 * @return array
	 */
	public function fetchPairs($query, array $query_data = array(), $key_column = null, $value_column = null) {
		$res = $this->fetchAll($query, $query_data);

		$result = array();

		foreach( $res as $row) {
			if($key_column===null) {
				list($key_column, $value_column) = array_keys($row);
			}
			$key = $row[$key_column];

			$result[$key] = $row[$value_column];
		}

		return $result;
	}

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 * @param string $column (optional, default:1st column)
	 *
	 * @return mixed
	 */
	public function fetchOne($query, array $query_data = array(), $column = null) {
		$res = $this->fetchAll($query, $query_data);

		foreach( $res as $row) {
			if($column===null) {
				list($column) = array_keys($row);
			}

			return $row[$column];
		}

		return false;
	}


	/**
	 * Begin a transaction.
	 */
	public function beginTransaction() {
		parent::beginTransaction();
		/*
		try {
			parent::beginTransaction();
		} catch(\PDOException $e) {
			if($e->getMessage()!="There is already an active transaction") {
				throw $e;
			}
		}
		*/

	}

	/**
	 * Roll back a transaction
	 *
	 */
	public function rollBack() {
		parent::rollBack();
		/*
		try {
			parent::rollBack();
		} catch(\PDOException $e) {
			if($e->getMessage()!="There is no active transaction") {
				throw $e;
			}
		}
		*/
	}

	/**
	 * Commit a transaction
	 */
	public function commit() {
		parent::commit();
		/*
		try {
			parent::commit();
		} catch(\PDOException $e) {
			if($e->getMessage()!="There is no active transaction") {
				throw $e;
			}
		}
		*/
	}

	public function disconnect() {

	}
}