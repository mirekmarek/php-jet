<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\TestModule;

use Jet\Application_Module;

/**
 *
 */
class Main extends Application_Module
{
	/**
	 * @return string
	 */
	public function getMyValue()
	{
		return 'My value';
	}

	/**
	 *
	 */
	public function testInstall()
	{

	}

}