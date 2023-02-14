<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

use PDO;
use PDOException;
use PDOStatement;

/**
 *
 */
class Db_Backend_PDO implements Db_Backend_Interface
{
	/**
	 *
	 * @var Db_Backend_PDO_Config
	 */
	protected Db_Backend_PDO_Config $config;

	protected ?PDO $pdo;

	protected array $statements = [];

	/**
	 * @param Db_Backend_Config $config
	 */
	public function __construct( Db_Backend_Config $config )
	{
		/**
		 * @var Db_Backend_PDO_Config $config
		 */

		$this->config = $config;

		$this->pdo = new PDO(
			dsn: $config->getDsn(),
			username: $config->getUsername(),
			password: $config->getPassword(),
			options: [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			]
		);

	}

	/**
	 *
	 */
	public function __destruct()
	{
		$this->disconnect();
	}

	/**
	 *
	 * @return Db_Backend_PDO_Config
	 */
	public function getConfig(): Db_Backend_PDO_Config
	{
		return $this->config;
	}

	protected function _q( string $query, array $query_params = [] ) : PDOStatement
	{
		$q_hash = md5($query);

		if(!isset($this->statements[$q_hash])) {
			$this->statements[$q_hash] = $this->pdo->prepare( $query );
		}

		$statement = $this->statements[$q_hash];

		$statement->execute( $query_params );

		return $statement;
	}


	/**
	 * @param string $query
	 * @param array $query_params
	 * @param ?callable $result_handler
	 *
	 * @return iterable
	 */
	public function query( string $query, array $query_params = [], ?callable $result_handler=null ): iterable
	{

		Debug_Profiler::SQLQueryStart( $query, $query_params );

		$statement = $this->_q($query, $query_params);


		if(!$result_handler) {
			$result = $statement;
		} else {
			$result = $result_handler( $statement );
		}

		Debug_Profiler::SQLQueryDone( $statement->rowCount() );

		return $result;
	}


	/**
	 * Executes command (INSERT, UPDATE, DELETE or CREATE, ...) and return affected rows
	 *
	 * @param string $query
	 * @param array $query_data
	 *
	 * @return int
	 */
	public function execute( string $query, array $query_data = [] ): int
	{
		Debug_Profiler::SQLQueryStart( $query, $query_data );

		$statement = $this->_q($query, $query_data);

		$count = $statement->rowCount();

		Debug_Profiler::SQLQueryDone( $count );

		return $count;
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
		$res = $this->query( $query, $query_data, function( PDOStatement $stn ) {
			foreach( $stn as $row ) {
				return $row;
			}

			return [];
		} );

		/**
		 * @var array $res
		 */
		if(!$res) {
			return false;
		}

		return $res;
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
		/**
		 * @var array $res
		 */
		$res =  $this->query( $query, $query_data, function( PDOStatement $stn ) {
			return $stn->fetchAll( PDO::FETCH_ASSOC );
		} );

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
		/**
		 * @var array $res
		 */
		$res =  $this->query( $query, $query_data, function( PDOStatement $stn ) use ($key_column) {
			$result = [];

			foreach( $stn as $row ) {
				if( $key_column === null ) {
					[$key_column] = array_keys( $row );
				}
				$key = $row[$key_column];

				$result[$key] = $row;
			}

			return $result;
		} );

		return $res;
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
		/**
		 * @var array $res
		 */
		$res =  $this->query( $query, $query_data, function( PDOStatement $stn ) use ($column) {
			$result = [];

			foreach( $stn as $row ) {
				if( $column === null ) {
					[$column] = array_keys( $row );
				}
				$result[] = $row[$column];
			}

			return $result;
		} );

		return $res;
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
		/**
		 * @var array $res
		 */
		$res =  $this->query( $query, $query_data, function( PDOStatement $stn ) use ($key_column, $value_column) {
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

			return $result;
		} );

		return $res;
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
		/**
		 * @var array $res
		 */
		$res =  $this->query( $query, $query_data, function( PDOStatement $stn ) use ($column) {
			foreach( $stn as $row ) {
				if( $column === null ) {
					[$column] = array_keys( $row );
				}

				return [$row[$column]];
			}

			return [false];
		} );

		return $res[0];
	}


	/**
	 *
	 */
	public function disconnect(): void
	{
		$this->pdo = null;
	}

	public function beginTransaction(): bool
	{
		return $this->pdo->beginTransaction();
	}

	public function commit(): bool
	{
		return $this->pdo->commit();
	}

	public function rollBack(): bool
	{
		return $this->pdo->rollBack();
	}

	public function inTransaction(): bool
	{
		return $this->pdo->inTransaction();
	}

	public function lastInsertId( string $name = null ): string
	{
		try {
			return $this->pdo->lastInsertId( $name );
		} catch( PDOException $e ) {
			return false;
		}
		
	}
	
	public function quoteString( string $string ): string
	{
		return $this->pdo->quote( $string );
	}
}