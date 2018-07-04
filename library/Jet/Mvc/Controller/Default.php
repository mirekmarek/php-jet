<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
abstract class Mvc_Controller_Default extends Mvc_Controller
{
	/**
	 *
	 *
	 */
	public function responseAccessDenied()
	{
		ErrorPages::handleUnauthorized();
		Application::end();
	}

}