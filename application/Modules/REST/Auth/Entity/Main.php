<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\REST\Auth\Entity;

use Jet\Application_Module;
use Jet\Application_Module_HasEmailTemplates_Interface;
use Jet\Application_Module_HasEmailTemplates_Trait;

class Main extends Application_Module implements Application_Module_HasEmailTemplates_Interface
{
	use Application_Module_HasEmailTemplates_Trait;
}