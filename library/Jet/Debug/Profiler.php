<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
	 * @var string|null
	 */
	protected static string|null $run_save_directory_path = null;


	/**
	 * @return string
	 */
	public static function getRunSaveDirectoryPath() : string
	{
		return static::$run_save_directory_path;
	}

	/**
	 * @param string $run_save_directory_path
	 */
	public static function setRunSaveDirectoryPath( string $run_save_directory_path ) : void
	{
		static::$run_save_directory_path = $run_save_directory_path;
	}


	/**
	 * @param callable|null $saver
	 * @param callable|null $displayer
	 * @param bool     $log_SQL_queries
	 */
	public static function enable(
		?callable $saver = null,
		?callable $displayer = null,
		$log_SQL_queries = true
	) : void
	{
		static::$run = new Debug_Profiler_Run();

		static::$enabled = true;
		static::$log_SQL_queries = $log_SQL_queries;


		register_shutdown_function(
			function() use ($saver, $displayer) {

				$run = Debug_Profiler::getRun();
				$run->runEnd();

				if($saver) {
					$saver( $run );
				}

				if($displayer) {
					$displayer( $run );
				}


			}
		);
	}

	/**
	 * @return bool
	 */
	public static function enabled() : bool
	{
		return static::$enabled;
	}

	/**
	 * @return bool
	 */
	public static function getLogSQLQueries() : bool
	{
		return static::$log_SQL_queries;
	}

	/**
	 * @return Debug_Profiler_Run|null
	 */
	public static function getRun() : Debug_Profiler_Run|null
	{
		return static::$run;
	}

	/**
	 * @param string $query
	 * @param array  $query_data
	 */
	public static function SQLQueryStart( string $query, $query_data = [] ) : void
	{
		if(
			!static::$enabled||
			!static::$log_SQL_queries
		) {
			return;
		}

		static::$run->SQLQueryStart( $query, $query_data );
	}

	/**
	 * @param int $rows_count
	 */
	public static function SQLQueryDone( int $rows_count = 0 ) : void
	{
		if(
			!static::$enabled||
			!static::$log_SQL_queries
		) {
			return;
		}

		static::$run->SqlQueryDone( $rows_count );
	}

	/**
	 * @param string $text
	 */
	public static function message( string $text ) : void
	{
		if( !static::$enabled ) {
			return;
		}

		static::$run->message( $text );
	}

	/**
	 * @param string $label
	 */
	public static function blockStart( string $label ) : void
	{
		if( !static::$enabled ) {
			return;
		}

		static::$run->blockStart( $label );
	}

	/**
	 *
	 * @param string $label
	 *
	 */
	public static function blockEnd( string $label ) : void
	{
		if( !static::$enabled ) {
			return;
		}

		static::$run->blockEnd( $label );
	}


	/**
	 *
	 * @param int $shift (optional, default: 0)
	 *
	 * @return array
	 */
	public static function getBacktrace( int $shift = 0 ) : array
	{
		$_backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

		if( $shift ) {
			for( $c = 0; $c<$shift; $c++ ) {
				array_shift( $_backtrace );
			}
		}

		$backtrace = [];

		foreach( $_backtrace as $bt ) {
			if( !isset( $bt['file'] ) ) {
				$backtrace[] = '?';
			} else {
				$backtrace[] = $bt['file'].':'.$bt['line'];
			}
		}

		return $backtrace;

	}


}
