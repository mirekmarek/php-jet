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
interface Db_BackendInterface
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
	public function disconnect();

	/**
	 *
	 * @return Db_Backend_Config
	 */
	public function getConfig();

	/**
	 *
	 * @param string $query
	 * @param array  $query_data
	 *
	 * @return string
	 */
	public function prepareQuery( $query, array $query_data = [] );

	/**
	 * Executes command (INSERT, UPDATE, DELETE or CREATE, ...) and return affected rows
	 *
	 * @param string $query
	 * @param array  $query_data
	 *
	 * @return int
	 */
	public function execCommand( $query, array $query_data = [] );

	/**
	 * @param string $statement
	 *
	 * @return object
	 */
	public function query( $statement );


	/**
	 *
	 * @param string $query
	 * @param array  $query_data (optional)
	 *
	 * @return array
	 */
	public function fetchAll( $query, array $query_data = [] );

	/**
	 *
	 * @param string $query
	 * @param array  $query_data (optional)
	 *
	 * @return array|bool
	 */
	public function fetchRow( $query, array $query_data = [] );

	/**
	 *
	 * @param string $query
	 * @param array  $query_data (optional)
	 * @param string $key_column (optional)
	 *
	 * @return array
	 */
	public function fetchAssoc( $query, array $query_data = [], $key_column = null );

	/**
	 *
	 * @param string $query
	 * @param array  $query_data (optional)
	 * @param string $column (optional, default: 1st column)
	 *
	 * @return array
	 */
	public function fetchCol( $query, array $query_data = [], $column = null );

	/**
	 *
	 * @param string $query
	 * @param array  $query_data (optional)
	 * @param string $key_column (optional, default: 1st column)
	 * @param string $value_column (optional, default: 2nd column)
	 *
	 * @return array
	 */
	public function fetchPairs( $query, array $query_data = [], $key_column = null, $value_column = null );

	/**
	 *
	 * @param string $query
	 * @param array  $query_data (optional)
	 * @param string $column (optional, default:1st column)
	 *
	 * @return mixed
	 */
	public function fetchOne( $query, array $query_data = [], $column = null );

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
	 * @return mixed
	 */
	public function quote( $string );

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function lastInsertId ($name = null);

}