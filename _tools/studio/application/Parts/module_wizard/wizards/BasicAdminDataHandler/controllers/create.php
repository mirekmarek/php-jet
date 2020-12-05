<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetStudio\ModuleWizard\BasicAdminDataHandler;

/**
 * @var Wizard $this
 */

if(
	$this->catchSetupForm() &&
	$this->create()
) {
	$this->redirectToModuleEditing();
}