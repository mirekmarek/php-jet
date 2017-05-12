<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require_once JET_LIBRARY_PATH.'Jet/Debug/Formatter.php';

/**
 * Class Debug_ErrorHandler_Handler_Log
 * @package Jet
 */
class Debug_ErrorHandler_Handler_Log extends Debug_ErrorHandler_Handler
{

	/**
	 * @return string
	 */
	public function getName() {
		return 'Display';
	}

	/**
	 * @var null|string
	 */
	protected $log_dir = null;

	/**
	 * @param Debug_ErrorHandler_Error $error
	 */
	public function handle( Debug_ErrorHandler_Error $error )
	{
		$message = Debug_Formatter::formatErrorMessage_TXT( $error );

		$dir = $this->getLogDir();

		if( !$dir ) {
			echo 'Warning! JET_LOGS_PATH is not defined!';
			echo $message;

			return;
		}


		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		$log_fn = $dir.'/'.@date( 'Y-m-d' ).'.log';

		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		if( !@file_put_contents(
			$log_fn,
			$message.'_________________________________________________________________________________________'.JET_EOL.JET_EOL.JET_EOL,
			FILE_APPEND
		)
		) {
			echo 'Warning! Log  file\''.$log_fn.'\' is not writable!';
			echo $message;

			return;
		}

		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@chmod( $log_fn, 0666 );
	}

	/**
	 * @param string $log_dir
	 */
	public function setLogDir( $log_dir )
	{
		$this->log_dir = $log_dir;
	}

	/**
	 * @return bool|null|string
	 */
	protected function getLogDir()
	{
		if(!$this->log_dir) {
			if( defined( 'JET_LOGS_PATH' ) ) {
				$this->log_dir = JET_LOGS_PATH;
			}
		}

		return $this->log_dir;

	}

	/**
	 * @return bool
	 */
	public function errorDisplayed()
	{
		return false;
	}
}