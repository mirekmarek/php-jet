<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/** @noinspection PhpIncludeInspection */
require_once JET_LIBRARY_PATH.'Jet/Debug/Tools/Formatter.php';

/**
 * Class Debug_ErrorHandler_Handler_Display
 * @package Jet
 */
class Debug_ErrorHandler_Handler_Display extends Debug_ErrorHandler_Handler_Abstract
{


	/**
	 *
	 * @var int
	 */
	protected static $id_counter = 0;

	/**
	 * @param Debug_ErrorHandler_Error $error
	 */
	public function handle( Debug_ErrorHandler_Error $error )
	{
		if( Debug_ErrorHandler::getHTMLErrorsEnabled() ) {
			echo Debug_Tools_Formatter::formatErrorMessage_HTML( $error );
		} else {
			echo Debug_Tools_Formatter::formatErrorMessage_TXT( $error );
		}
	}

	/**
	 * @return bool
	 */
	public function errorDisplayed()
	{
		return true;
	}
}