<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace Jet;

use \PDO;
use \PDOStatement;

/**
 *
 */
class Db_Backend_PDO extends PDO implements Db_Backend_Interface
{
	/**
	 *
	 * @var Db_Backend_PDO_Config
	 */
	protected Db_Backend_PDO_Config $config;

	/**
	 * @param Db_Backend_Config $config
	 */
	public function __construct( Db_Backend_Config $config )
	{
		/**
		 * @var Db_Backend_PDO_Config $config
		 */

		$this->config = $config;

		parent::__construct( $config->getDsn(), $config->getUsername(), $config->getPassword() );

		$this->setAttribute( static::ATTR_ERRMODE, static::ERRMODE_EXCEPTION );

	}

	/**
	 *
	 */
	public function __destruct()
	{
		try {
			$this->disconnect();
		} catch( \Exception $e ) {
		}
	}

	/**
	 *
	 * @return Db_Backend_PDO_Config
	 */
	public function getConfig(): Db_Backend_PDO_Config
	{
		return $this->config;
	}


	/**
	 *
	 * @param mixed $statement
	 *
	 * @return int|false
	 * @noinspection PhpMissingParamTypeInspection
	 */
	public function exec( $statement )
	{
		Debug_Profiler::SQLQueryStart( $statement );

		$result = parent::exec( $statement );

		Debug_Profiler::SQLQueryDone( $result );

		return $result;
	}

	/**
	 * @param string $statement
	 *
	 * @return PDOStatement
	 */
	public function doQuery( string $statement ): PDOStatement
	{
		Debug_Profiler::SQLQueryStart( $statement );

		$res = $this->query( $statement );
		Debug_Profiler::SQLQueryDone();

		return $res;
	}

	/**
	 * Executes command (INSERT, UPDATE, DELETE or CREATE, ...) and return affected rows
	 *
	 * @param string $query
	 * @param array $query_data
	 *
	 * @return int
	 */
	public function execCommand( string $query, array $query_data = [] ): int
	{
		Debug_Profiler::SQLQueryStart( $query, $query_data );

		$statement = $this->prepare( $query );

		foreach( $query_data as $k => $v ) {
			$type = PDO::PARAM_STR;
			$len = null;

			if( is_int( $v ) ) {
				$type = PDO::PARAM_INT;
			} else if( is_null( $v ) ) {
				$type = PDO::PARAM_NULL;
			} else if( is_string( $v ) ) {
				$len = strlen( $v );
			}

			$statement->bindParam( $k, $query_data[$k], $type, $len );

		}

		$res = $statement->execute();

		Debug_Profiler::SQLQueryDone( $res );

		return $res;
	}

	/**
	 *
	 * @param string $query
	 * @param array $query_data
	 *
	 * @return string
	 */
	public function prepareQuery( string $query, array $query_data = [] ): string
	{

		if( !$query_data ) {
			return $query;
		}


		$replacements = [];

		foreach( $query_data as $key => $value ) {

			if( $value === null ) {
				$value = 'NULL';
			} else if( is_bool( $value ) ) {
				$value = $value ? 1 : 0;
			} else if( is_int( $value ) || is_float( $value ) ) {

			} else {
				$value = $this->quote( (string)$value );

			}

			$replacements[':' . $key] = $value;
		}

		krsort( $replacements, SORT_STRING );

		return str_replace(
			array_keys( $replacements ), array_values( $replacements ), $query
		);

	}


	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 *
	 * @return array|bool
	 */
	public function fetchRow( string $query, array $query_data = [] ): array|bool
	{
		$q = $this->prepareQuery( $query, $query_data );

		Debug_Profiler::SQLQueryStart( $q, $query_data );
		$stn = $this->query( $q );
		Debug_Profiler::SQLQueryDone();

		foreach( $stn as $row ) {
			return $row;
		}

		return false;
	}

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 *
	 * @return array
	 */
	public function fetchAll( string $query, array $query_data = [] ): array
	{
		$q = $this->prepareQuery( $query, $query_data );

		Debug_Profiler::SQLQueryStart( $q, $query_data );
		$stn = $this->query( $q );
		$res = $stn->fetchAll( PDO::FETCH_ASSOC );

		Debug_Profiler::SQLQueryDone( count( $res ) );

		return $res;
	}

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 * @param string|null $key_column (optional)
	 *
	 * @return array
	 */
	public function fetchAssoc( string $query, array $query_data = [], ?string $key_column = null ): array
	{
		$q = $this->prepareQuery( $query, $query_data );

		Debug_Profiler::SQLQueryStart( $q, $query_data );
		$stn = $this->query( $q );

		$result = [];

		foreach( $stn as $row ) {
			if( $key_column === null ) {
				[$key_column] = array_keys( $row );
			}
			$key = $row[$key_column];

			$result[$key] = $row;
		}
		Debug_Profiler::SQLQueryDone( count( $result ) );

		return $result;

	}

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 * @param string|null $column (optional, default: 1st column)
	 *
	 * @return array
	 */
	public function fetchCol( string $query, array $query_data = [], ?string $column = null ): array
	{
		$q = $this->prepareQuery( $query, $query_data );

		Debug_Profiler::SQLQueryStart( $q, $query_data );
		$stn = $this->query( $q );

		$result = [];
		foreach( $stn as $row ) {
			if( $column === null ) {
				[$column] = array_keys( $row );
			}
			$result[] = $row[$column];
		}
		Debug_Profiler::SQLQueryDone( count( $result ) );

		return $result;
	}

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 * @param string|null $key_column (optional, default: 1st column)
	 * @param string|null $value_column (optional, default: 2nd column)
	 *
	 * @return array
	 */
	public function fetchPairs( string $query, array $query_data = [], ?string $key_column = null, ?string $value_column = null ): array
	{
		$q = $this->prepareQuery( $query, $query_data );

		Debug_Profiler::SQLQueryStart( $q, $query_data );
		$stn = $this->query( $q );

		$result = [];

		foreach( $stn as $row ) {
			if( $key_column === null ) {
				[
					$key_column,
					$value_column
				] = array_keys( $row );
			}
			$key = $row[$key_column];

			$result[$key] = $row[$value_column];
		}
		Debug_Profiler::SQLQueryDone( count( $result ) );

		return $result;
	}

	/**
	 *
	 * @param string $query
	 * @param array $query_data (optional)
	 * @param string|null $column (optional, default:1st column)
	 *
	 * @return mixed
	 */
	public function fetchOne( string $query, array $query_data = [], ?string $column = null ): mixed
	{
		$q = $this->prepareQuery( $query, $query_data );

		Debug_Profiler::SQLQueryStart( $q, $query_data );
		$stn = $this->query( $q );
		Debug_Profiler::SQLQueryDone();

		foreach( $stn as $row ) {
			if( $column === null ) {
				[$column] = array_keys( $row );
			}

			return $row[$column];
		}

		return false;
	}


	/**
	 *
	 */
	public function disconnect(): void
	{
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public function quoteString( string $string ): string
	{
		return $this->quote( $string, PDO::PARAM_STR );
	}
}