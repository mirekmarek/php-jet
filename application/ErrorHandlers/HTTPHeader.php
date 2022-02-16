<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Debug_ErrorHandler_Handler;
use Jet\Debug_ErrorHandler_Error;

/**
 *
 */
class ErrorHandler_HTTPHeader extends Debug_ErrorHandler_Handler
{
	/**
	 * @return string
	 */
	public function getName(): string
	{
		return 'HTTPHeader';
	}

	/**
	 * @param Debug_ErrorHandler_Error $error
	 */
	public function handle( Debug_ErrorHandler_Error $error ): void
	{
		if(
			$error->isFatal() &&
			php_sapi_name() != 'cli' &&
			!headers_sent()
		) {
			header( 'HTTP/1.1 500 Internal Server Error' );
		}
	}

	/**
	 * @return bool
	 */
	public function errorDisplayed(): bool
	{
		return false;
	}
}