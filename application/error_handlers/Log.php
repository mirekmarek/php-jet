<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Debug_ErrorHandler_Handler;
use Jet\Debug_ErrorHandler_Error;

/**
 *
 */
class ErrorHandler_Log extends Debug_ErrorHandler_Handler
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
		$message = $error->toString();

		$dir = $this->getLogDir();

		if( !$dir ) {
			echo 'Warning! JET_PATH_LOGS is not defined!';
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
			if( defined( 'JET_PATH_LOGS' ) ) {
				$this->log_dir = JET_PATH_LOGS;
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