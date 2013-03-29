<?php
/**
 *
 * @copyright Copyright (c) 2011-2012 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Db
 */
namespace Jet;

class Db_Adapter_PDO extends Db_Adapter_Abstract {

	/**
	 * @var \PDO
	 */
	protected $PDO;

	/**
	 * @param Db_Adapter_PDO_Config $config
	 */
	public function __construct( Db_Adapter_PDO_Config $config ) {
		parent::__construct($config);

		$this->PDO = new \PDO( $config->getDsn(), $config->getUsername(), $config->getPassword() );
		$this->PDO->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	/**
	 * Executes query and return affected rows
	 *
	 * @param string $query
	 * @param array $query_data
	 *
	 * @return int
	 */
	public function query($query, array $query_data = array()) {
		$q = $this->prepareQuery($query, $query_data);
		Debug_Profiler::SQLQueryStart($q);
		$result = $this->PDO->exec( $q );
		Debug_Profiler::SQLQueryEnd();
		return $result;
	}

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 * @return array
	 */
	public function fetchAll($query, array $query_data = array()) {
		$q = $this->prepareQuery($query, $query_data);
		Debug_Profiler::SQLQueryStart($q);

		$res = $this->PDO->query( $q );

		$res->setFetchMode( \PDO::FETCH_NAMED );

		$result = array();

		foreach( $res as $row) {
			$result[] = $row;
		}

		Debug_Profiler::SQLQueryEnd( count($result) );

		return $result;
	}

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 *
	 * @return array|bool
	 */
	public function fetchRow($query, array $query_data = array()) {
		$q = $this->prepareQuery($query, $query_data);
		Debug_Profiler::SQLQueryStart($q);

		$res = $this->PDO->query( $q );

		$res->setFetchMode( \PDO::FETCH_NAMED );

		foreach( $res as $row) {
			Debug_Profiler::SQLQueryEnd(1);

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
		$q = $this->prepareQuery($query, $query_data);
		Debug_Profiler::SQLQueryStart($q);

		$res = $this->PDO->query( $q );

		$res->setFetchMode( \PDO::FETCH_NAMED );

		$result = array();

		foreach( $res as $row) {
			if($key_column===null) {
				list($key_column) = array_keys($row);
			}
			$key = $row[$key_column];

			$result[$key] = $row;
		}

		Debug_Profiler::SQLQueryEnd( count($result) );

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
		$q = $this->prepareQuery($query, $query_data);
		Debug_Profiler::SQLQueryStart($q);

		$res = $this->PDO->query( $q );

		$res->setFetchMode( \PDO::FETCH_NAMED );

		$result = array();
		foreach( $res as $row) {
			if($column===null) {
				list($column) = array_keys($row);
			}
			$result[] =  $row[$column];
		}

		Debug_Profiler::SQLQueryEnd( count($result) );

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
		$q = $this->prepareQuery($query, $query_data);
		Debug_Profiler::SQLQueryStart($q);

		$res = $this->PDO->query( $q );

		$res->setFetchMode( \PDO::FETCH_NAMED );

		$result = array();

		foreach( $res as $row) {
			if($key_column===null) {
				list($key_column, $value_column) = array_keys($row);
			}
			$key = $row[$key_column];

			$result[$key] = $row[$value_column];
		}

		Debug_Profiler::SQLQueryEnd( count($result) );

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
		$q = $this->prepareQuery($query, $query_data);
		Debug_Profiler::SQLQueryStart($q);

		$res = $this->PDO->query( $q );

		$res->setFetchMode( \PDO::FETCH_NAMED );

		foreach( $res as $row) {
			if($column===null) {
				list($column) = array_keys($row);
			}
			Debug_Profiler::SQLQueryEnd(1);

			return $row[$column];
		}

		return false;
	}

	/**
	 * @param string|null $name (optional)
	 *
	 * @return mixed
	 */
	public function lastInsertId( $name = null ) {
		return $this->PDO->lastInsertId($name);
	}

	/**
	 *
	 * @param string $string
	 * @return string
	 */
	public function quote($string) {
		return $this->PDO->quote($string);
	}

	/**
	 * Begin a transaction.
	 */
	public function beginTransaction() {
		$this->PDO->beginTransaction();
		/*
		try {
			$this->PDO->beginTransaction();
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
		$this->PDO->rollBack();
		/*
		try {
			$this->PDO->rollBack();
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
		$this->PDO->commit();
		/*
		try {
			$this->PDO->commit();
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