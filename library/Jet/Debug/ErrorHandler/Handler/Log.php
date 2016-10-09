<?php
/**
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Debug
 * @subpackage Debug_ErrorHandler
 */
namespace Jet;

require_once JET_LIBRARY_PATH.'Jet/Debug/Tools/Formatter.php';

class Debug_ErrorHandler_Handler_Log extends Debug_ErrorHandler_Handler_Abstract {

	/**
	 * @var null|string
	 */
	protected $log_dir = null;

	/**
	 * @return bool|null|string
	 */
	protected function getLogDir() {
		if( !empty($this->log_dir) ) {
			$dir = $this->log_dir;
		} else {
			if( !defined('JET_LOGS_PATH') ) {
				return false;
			}

			$dir = JET_LOGS_PATH;
		}

		return $dir;

	}

	/**
	 * @param Debug_ErrorHandler_Error $error
	 */
	public function handle( Debug_ErrorHandler_Error $error ) {
		$message = Debug_Tools_Formatter::formatErrorMessage_TXT($error);

		$dir = $this->getLogDir();

		if(!$dir) {
			echo 'Warning! JET_LOGS_PATH is not defined!';
			echo $message;
			return;
		}


		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		$log_fn = $dir.'/'.@date('Y-m-d').'.log';

		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		if(!@file_put_contents($log_fn,
					$message.
					'_________________________________________________________________________________________'.JET_EOL.JET_EOL.JET_EOL,
					FILE_APPEND
		)) {
			echo 'Warning! Log  file\''.$log_fn.'\' is not writable!';
			echo $message;
			return;
		}

		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		@chmod($log_fn, 0666);
	}

	/**
	 * @return bool
	 */
	public function errorDisplayed() {
		return false;
	}
}