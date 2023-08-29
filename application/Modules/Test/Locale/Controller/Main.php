<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\Locale;

use Jet\Http_Request;
use Jet\Locale;
use Jet\MVC_Controller_Default;

/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{
	/**
	 *
	 */
	public function test_locale_Action(): void
	{
		$locale = Http_Request::GET()->getString(
			key: 'locale',
			default_value: Locale::getCurrentLocale()->toString(),
			valid_values: array_keys(Locale::getAllLocalesList())
		);
		
		$locale = new Locale($locale);
		
		$this->view->setVar('locale', $locale);
		
		$this->output( 'test-locale' );
	}
}