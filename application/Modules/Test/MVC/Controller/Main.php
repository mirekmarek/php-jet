<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\MVC;

use Jet\MVC_Controller_Default;

/**
 *
 */
class Controller_Main extends MVC_Controller_Default
{
	/**
	 *
	 */
	public function test_mvc_info_Action(): void
	{
		$this->output( 'test-mvc-info' );
	}
}