<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require_once JET_PATH_LIBRARY.'Jet/Debug/Profiler/Run/SQLQueryData.php';
require_once JET_PATH_LIBRARY.'Jet/Debug/Profiler/Run/Block/Message.php';
require_once JET_PATH_LIBRARY.'Jet/Debug/Profiler/Run/Block.php';
require_once JET_PATH_LIBRARY.'Jet/Debug/Profiler/Run.php';


/**
 * Class Debug_Profiler
 * @package Jet
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
	protected static $output_enabled = true;

	/**
	 * @var bool
	 */
	protected static $output_is_XML = false;

	/**
	 * @var bool
	 */
	protected static $output_is_JSON = false;

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
	 * @param bool $log_SQL_queries
	 */
	public static function enable( $log_SQL_queries = true )
	{
		static::$run = new Debug_Profiler_Run();

		static::$enabled = true;
		static::$log_SQL_queries = $log_SQL_queries;


		register_shutdown_function(
			function() {
				$run = Debug_Profiler::getRun();
				$run->runEnd();
				$run_id = $run->getId();

				Debug_Profiler::saveRun();

				if( !static::$output_enabled ) {
					return;
				}

				$URL = '?JPR&run='.$run_id;

				if( static::$output_is_XML ) {
					echo '<!-- profiler: '.$URL.' -->';
				} elseif( static::$output_is_JSON ) {
					//echo '//profiler: '.$URL;
				} else {
					echo '<div><a href="'.$URL.'" target="_blank">profiler</a></div>';
				}

			}
		);
	}

	/**
	 * @return Debug_Profiler_Run
	 */
	public static function getRun()
	{
		return static::$run;
	}

	/**
	 *
	 */
	public static function saveRun()
	{
		$run = static::getRun();
		$run_id = $run->getId();

		$dir = static::getRunSaveDirectoryPath();

		if( !file_exists( $dir ) ) {
			/** @noinspection PhpUsageOfSilenceOperatorInspection */
			@mkdir( $dir );
			/** @noinspection PhpUsageOfSilenceOperatorInspection */
			@chmod( $dir, 0777 );
		}
		$file_path = $dir.$run_id.'.jpd';
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@file_put_contents( $file_path, serialize( $run ) );
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@chmod( $file_path, 0666 );
	}

	/**
	 * @return string
	 */
	public static function getRunSaveDirectoryPath()
	{
		if( !static::$run_save_directory_path ) {
			static::$run_save_directory_path = JET_PATH_TMP.'_profiler/';
		}

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
	 * @return bool
	 */
	public static function getEnabled()
	{
		return static::$enabled;
	}

	/**
	 * @param bool $enabled
	 */
	public static function setEnabled( $enabled )
	{
		static::$enabled = $enabled;
	}

	/**
	 * @param bool $output_enabled
	 */
	public static function setOutputEnabled( $output_enabled )
	{
		static::$output_enabled = $output_enabled;
	}

	/**
	 * @param bool $output_is_JSON
	 */
	public static function setOutputIsJSON( $output_is_JSON )
	{
		static::$output_is_JSON = $output_is_JSON;
	}

	/**
	 * @param bool $output_is_XML
	 */
	public static function setOutputIsXML( $output_is_XML )
	{
		static::$output_is_XML = $output_is_XML;
	}

	/**
	 * @param string $run_id
	 *
	 * @return Debug_Profiler_Run|null
	 * @throws \Exception
	 */
	public static function loadRun( $run_id )
	{
		if( strpos( $run_id, '.' )!==false ) {
			return null;
		}

		$dir = static::getRunSaveDirectoryPath();

		$file_path = $dir.$run_id.'.jpd';

		if( !file_exists( $file_path ) ) {
			return null;
		}

		$d = file_get_contents( $file_path );

		$run = unserialize( $d );

		if( !is_object( $run )||!( $run instanceof Debug_Profiler_Run ) ) {
			return null;
		}

		return $run;
	}

	/**
	 * @param string $query
	 * @param array  $query_data
	 */
	public static function SQLQueryStart( $query, $query_data = [] )
	{
		if( !static::$enabled||!static::$log_SQL_queries ) {
			return;
		}

		static::$run->SQLQueryStart( $query, $query_data );
	}

	/**
	 * @param int $rows_count
	 */
	public static function SQLQueryDone( $rows_count = 0 )
	{
		if( !static::$enabled||!static::$log_SQL_queries ) {
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
	public static function MainBlockStart( $label )
	{
		if( !static::$enabled ) {
			return;
		}

		static::$run->MainBlockStart( $label );
	}

	/**
	 * @param string $label
	 */
	public static function MainBlockEnd( $label )
	{
		if( !static::$enabled ) {
			return;
		}

		static::$run->MainBlockEnd( $label );
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
		$_backtrace = debug_backtrace();

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
