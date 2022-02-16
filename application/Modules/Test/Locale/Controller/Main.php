<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\Locale;

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
		$this->output( 'test-locale' );
	}
}