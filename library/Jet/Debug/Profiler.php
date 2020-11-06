<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected static $enabled = false;

	/**
	 * @var bool
	 */
	protected static $log_SQL_queries = false;

	/**
	 * @var Debug_Profiler_Run
	 */
	protected static $run;

	/**
	 * @var string
	 */
	protected static $run_save_directory_path;


	/**
	 * @return string
	 */
	public static function getRunSaveDirectoryPath()
	{
		return static::$run_save_directory_path;
	}

	/**
	 * @param string $run_save_directory_path
	 */
	public static function setRunSaveDirectoryPath( $run_save_directory_path )
	{
		static::$run_save_directory_path = $run_save_directory_path;
	}


	/**
	 * @param callable|null $saver
	 * @param callable|null $displayer
	 * @param bool     $log_SQL_queries
	 */
	public static function enable(
		callable $saver = null,
		callable $displayer = null,
		$log_SQL_queries = true
	)
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
	public static function enabled()
	{
		return static::$enabled;
	}

	/**
	 * @return bool
	 */
	public static function getLogSQLQueries()
	{
		return static::$log_SQL_queries;
	}

	/**
	 * @return Debug_Profiler_Run
	 */
	public static function getRun()
	{
		return static::$run;
	}

	/**
	 * @param string $query
	 * @param array  $query_data
	 */
	public static function SQLQueryStart( $query, $query_data = [] )
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
	public static function SQLQueryDone( $rows_count = 0 )
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
	public static function message( $text )
	{
		if( !static::$enabled ) {
			return;
		}

		static::$run->message( $text );
	}

	/**
	 * @param string $label
	 */
	public static function blockStart( $label )
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
	public static function blockEnd( $label )
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
	public static function getBacktrace( $shift = 0 )
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
