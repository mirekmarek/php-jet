<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio\ModuleWizard\Basic;

/**
 * @var Wizard $this
 */

if(
	$this->catchSetupForm() &&
	$this->create()
) {
	$this->redirectToModuleEditing();
}