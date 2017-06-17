<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplicationModule\JetExample\Test\Locale;


use Jet\Mvc_Controller_Default;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Default
{
	/**
	 * @var array
	 */
	protected static $ACL_actions_check_map = [
	    'test_locale'  => false
	];

	/**
	 *
	 * @var Main
	 */
	protected $module = null;



	/**
	 *
	 */
	public function test_locale_Action()
	{
		$this->render( 'test-locale' );

	}

}