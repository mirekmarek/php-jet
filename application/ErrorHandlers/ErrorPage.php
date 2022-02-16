<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Debug;
use Jet\Debug_ErrorHandler_Handler;
use Jet\Debug_ErrorHandler_Error;
use Jet\ErrorPages;

/**
 *
 */
class ErrorHandler_ErrorPage extends Debug_ErrorHandler_Handler
{
	/**
	 * @var bool
	 */
	protected bool $displayed = false;

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return 'ErrorPage';
	}

	/**
	 * @param Debug_ErrorHandler_Error $error
	 */
	public function handle( Debug_ErrorHandler_Error $error ): void
	{
		if(
			$error->isFatal() &&
			Debug::getOutputIsHTML() &&
			class_exists(ErrorPages::class, false)
		) {
			if( ErrorPages::display( 500 ) ) {
				$this->displayed = true;
			}
		}

	}

	/**
	 * @return bool
	 */
	public function errorDisplayed(): bool
	{
		return $this->displayed;
	}

}