<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetStudioModule\Welcome;

use JetStudio\JetStudio_Module_Controller;


class Controller extends JetStudio_Module_Controller
{
	public function default_Action() : void
	{
		$this->output('welcome');
	}
}