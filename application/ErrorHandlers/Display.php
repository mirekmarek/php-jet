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
use PhpToken;

/**
 *
 */
class ErrorHandler_Display extends Debug_ErrorHandler_Handler
{
	protected array $non_fatal_errors = [];
	
	protected bool $non_fatal_displayer_registered = false;
	
	/**
	 * @return string
	 */
	public function getName(): string
	{
		return 'Display';
	}

	/**
	 * @param Debug_ErrorHandler_Error $error
	 */
	public function handle( Debug_ErrorHandler_Error $error ): void
	{
		if($error->isSilenced()) {
			return;
		}

		if( Debug::getOutputIsHTML() ) {
			$this->display( $error );
		} else {
			echo $error->toString();
		}
	}

	/**
	 * @return bool
	 */
	public function errorDisplayed(): bool
	{
		return true;
	}

	/**
	 *
	 * @param Debug_ErrorHandler_Error $error
	 *
	 */
	public function display( Debug_ErrorHandler_Error $error ): void
	{
		if($error->isFatal()) {
			while (ob_get_level())
			{
				ob_end_clean();
			}
			
			$handler = $this;
			
			require __DIR__.'/views/fatal-error.phtml';
			return;
		}
		
		$this->non_fatal_errors[] = $error;

		if( !$this->non_fatal_displayer_registered ) {
			register_shutdown_function( function() {
				$errors = $this->non_fatal_errors;
				$handler = $this;
				
				require __DIR__.'/views/warnings.phtml';
			} );
			
			$this->non_fatal_displayer_registered = true;
		}
	}
	
}