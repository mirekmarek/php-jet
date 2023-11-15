<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication;

use Jet\Application_Module;

abstract class Application_Web_Services_Auth_LoginModule extends Application_Module
{
	abstract public function handleLogin( Application_Web_Services_Auth_Controller $auth_controller ): void;
}