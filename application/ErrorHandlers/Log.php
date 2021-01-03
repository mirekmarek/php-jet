<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Debug_ErrorHandler_Handler;
use Jet\Debug_ErrorHandler_Error;
use Jet\SysConf_Path;

/**
 *
 */
class ErrorHandler_Log extends Debug_ErrorHandler_Handler
{

	/**
	 * @return string
	 */
	public function getName() : string
	{
		return 'Display';
	}

	/**
	 * @var null|string
	 */
	protected ?string $log_dir = null;

	/**
	 * @param Debug_ErrorHandler_Error $error
	 */
	public function handle( Debug_ErrorHandler_Error $error ) : void
	{
		$message = $error->toString();

		$dir = $this->getLogDir();


		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		$log_fn = $dir.'/'.@date( 'Y-m-d' ).'.log';

		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		if( !@file_put_contents(
			$log_fn,
			$message.'_________________________________________________________________________________________'.PHP_EOL.PHP_EOL.PHP_EOL,
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
	public function setLogDir( string $log_dir ) : void
	{
		$this->log_dir = $log_dir;
	}

	/**
	 * @return bool|null|string
	 */
	protected function getLogDir() : bool|null|string
	{
		if(!$this->log_dir) {
			$this->log_dir = SysConf_Path::LOGS();
		}

		return $this->log_dir;

	}

	/**
	 * @return bool
	 */
	public function errorDisplayed() : bool
	{
		return false;
	}

}