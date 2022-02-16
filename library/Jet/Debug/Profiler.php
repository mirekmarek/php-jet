<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

require_once 'Profiler/Run/SQLQueryData.php';
require_once 'Profiler/Run/Block/Message.php';
require_once 'Profiler/Run/Block.php';
require_once 'Profiler/Run/Block/Anonymous.php';
require_once 'Profiler/Run.php';


/**
 *
 */
class Debug_Profiler
{
	/**
	 * @var bool
	 */
	protected static bool $enabled = false;

	/**
	 * @var bool
	 */
	protected static bool $log_SQL_queries = false;

	/**
	 * @var Debug_Profiler_Run|null
	 */
	protected static Debug_Profiler_Run|null $run = null;



	/**
	 * @param bool $log_SQL_queries
	 * @param callable $saver
	 * @param callable|null $displayer
	 */
	public static function enable(
		bool $log_SQL_queries,
		callable $saver,
		?callable $displayer = null,
	): void
	{
		static::$run = new Debug_Profiler_Run();

		static::$enabled = true;
		static::$log_SQL_queries = $log_SQL_queries;


		register_shutdown_function(
			function() use ( $saver, $displayer ) {

				$run = Debug_Profiler::getRun();
				$run->runEnd();

				$saver( $run );

				if( $displayer ) {
					$displayer( $run );
				}
			}
		);
	}

	/**
	 * @return bool
	 */
	public static function enabled(): bool
	{
		return static::$enabled;
	}

	/**
	 * @return bool
	 */
	public static function getLogSQLQueries(): bool
	{
		return static::$log_SQL_queries;
	}

	/**
	 * @return Debug_Profiler_Run|null
	 */
	public static function getRun(): Debug_Profiler_Run|null
	{
		return static::$run;
	}

	/**
	 * @param string $query
	 * @param array $query_params
	 */
	public static function SQLQueryStart( string $query, array $query_params = [] ): void
	{
		if( static::$log_SQL_queries ) {
			static::$run?->SQLQueryStart( $query, $query_params );
		}
	}

	/**
	 * @param int $rows_count
	 */
	public static function SQLQueryDone( int $rows_count = 0 ): void
	{
		if( static::$log_SQL_queries ) {
			static::$run?->SqlQueryDone( $rows_count );
		}
	}

	/**
	 * @param string $text
	 */
	public static function message( string $text ): void
	{
		static::$run?->message( $text );
	}

	/**
	 * @param string $label
	 */
	public static function blockStart( string $label ): void
	{
		static::$run?->blockStart( $label );
	}

	/**
	 *
	 * @param string $label
	 *
	 */
	public static function blockEnd( string $label ): void
	{
		static::$run?->blockEnd( $label );
	}

}
