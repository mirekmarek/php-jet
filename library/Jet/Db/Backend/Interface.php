<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
interface Db_Backend_Interface
{

	/**
	 * @param Db_Backend_Config $config
	 */
	public function __construct( Db_Backend_Config $config );

	/**
	 *
	 */
	public function __destruct();

	/**
	 *
	 */
	public function disconnect() : void;

	/**
	 *
	 * @return Db_Backend_Config
	 */
	public function getConfig() : Db_Backend_Config;

	/**
	 *
	 * @param string $query
	 * @param array  $query_data
	 *
	 * @return string
	 */
	public function prepareQuery( string $query, array $query_data = [] ) : string;

	/**
	 * Executes command (INSERT, UPDATE, DELETE or CREATE, ...) and return affected rows
	 *
	 * @param string $query
	 * @param array  $query_data
	 *
	 * @return int
	 */
	public function execCommand( string $query, array $query_data = [] ) : int;

	/**
	 * @param string $statement
	 *
	 * @return object
	 */
	public function doQuery( string $statement ) : object;


	/**
	 *
	 * @param string $query
	 * @param array  $query_data (optional)
	 *
	 * @return array
	 */
	public function fetchAll( string $query, array $query_data = [] ) : array;

	/**
	 *
	 * @param string $query
	 * @param array  $query_data (optional)
	 *
	 * @return array|bool
	 */
	public function fetchRow( string $query, array $query_data = [] ) : array|bool;

	/**
	 *
	 * @param string $query
	 * @param array  $query_data (optional)
	 * @param string|null $key_column (optional)
	 *
	 * @return array
	 */
	public function fetchAssoc( string $query, array $query_data = [], ?string $key_column = null ) : array;

	/**
	 *
	 * @param string $query
	 * @param array  $query_data (optional)
	 * @param string|null $column (optional, default: 1st column)
	 *
	 * @return array
	 */
	public function fetchCol( string $query, array $query_data = [], ?string $column = null ) : array;

	/**
	 *
	 * @param string $query
	 * @param array  $query_data (optional)
	 * @param string|null $key_column (optional, default: 1st column)
	 * @param string|null $value_column (optional, default: 2nd column)
	 *
	 * @return array
	 */
	public function fetchPairs( string $query, array $query_data = [], ?string $key_column = null, ?string $value_column = null ) : array;

	/**
	 *
	 * @param string $query
	 * @param array  $query_data (optional)
	 * @param string|null $column (optional, default:1st column)
	 *
	 * @return mixed
	 */
	public function fetchOne( string $query, array $query_data = [], ?string $column = null ) : mixed;

	/**
	 * @return bool
	 */
	public function beginTransaction();

	/**
	 * @return bool
	 */
	public function commit();

	/**
	 * @return bool
	 */
	public function rollBack();

	/**
	 * @return bool
	 */
	public function inTransaction();

	/**
	 * @param string $string
	 *
	 * @return string
	 */
	public function quoteString( string $string ) : string;

	/**
	 * @param string|null $name
	 *
	 * @return string
	 */
	public function lastInsertId ( string $name = null);

}