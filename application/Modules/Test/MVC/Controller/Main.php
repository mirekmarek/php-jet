<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\Test\MVC;

use Jet\Mvc_Controller_Default;

/**
 *
 */
class Controller_Main extends Mvc_Controller_Default
{
	/**
	 * @var array
	 */
	const ACL_ACTIONS_MAP = [
		'test_mvc_info'    => false,
	];

	/**
	 *
	 * @var Main
	 */
	protected $module = null;


	/**
	 *
	 */
	public function test_mvc_info_Action()
	{
		$this->render( 'test-mvc-info' );
	}


}