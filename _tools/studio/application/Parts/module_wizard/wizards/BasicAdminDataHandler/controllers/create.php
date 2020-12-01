<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio\ModuleWizard\BasicAdminDataHandler;

use Jet\Http_Headers;
use Jet\Tr;
use Jet\UI_messages;
use JetStudio\Application;

/**
 * @var Wizard $this
 */

if($this->catchSetupForm()) {
	if($this->create()) {

		UI_messages::success( Tr::_('Module <b>%NAME%</b> has been created', ['NAME'=>$this->module_name], 'module_wizard') );
		Http_Headers::movedTemporary('modules.php?module='.urlencode($this->module_name));
		Application::end();
	}
}

