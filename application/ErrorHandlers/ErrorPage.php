<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
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
	protected $displayed = false;

	/**
	 * @return string
	 */
	public function getName() {
		return 'ErrorPage';
	}

	/**
	 * @param Debug_ErrorHandler_Error $error
	 */
	public function handle( Debug_ErrorHandler_Error $error )
	{
		if(
			$error->isFatal() &&
			Debug::getOutputIsHTML()
		) {
			if(class_exists('Jet\ErrorPages', false)) {
				if(ErrorPages::display( 500 )) {
					$this->displayed = true;
				}
			}
		}

	}

	/**
	 * @return bool
	 */
	public function errorDisplayed()
	{
		return $this->displayed;
	}

}