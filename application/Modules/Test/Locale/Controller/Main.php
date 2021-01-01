<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\Test\Locale;

use Jet\Mvc_Controller_Default;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Default
{
	/**
	 *
	 */
	public function test_locale_Action() : void
	{
		$this->render( 'test-locale' );
	}
}