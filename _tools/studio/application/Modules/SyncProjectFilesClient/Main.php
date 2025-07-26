<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\SyncProjectFilesClient;

use JetStudio\JetStudio_Module;
use JetStudio\JetStudio_Module_Service_SetupModule;


class Main extends JetStudio_Module implements JetStudio_Module_Service_SetupModule
{
	
	public function handleSetup(): string
	{
		$view = $this->getView();
		
		$config = $this->getConfig();
		$config->handleCatchSetupForm();
		
		$view->setVar('config', $config);
		
		
		return $view->render('setup');
	}
}
