<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudioModule\ApplicationModuleWizard\Basic;

use Jet\Form;
use JetStudioModule\ApplicationModuleWizard\Wizard;


class Main extends Wizard
{
	
	protected string $title = 'Basic module';
	
	protected string $description = 'Create basic module without any extra features. Only very basic skeleton.';
	
	public function init(): void
	{
		$this->values = [
			//'NAMESPACE' => '',
			'COPYRIGHT'   => '',
			'LICENSE'     => '',
			'AUTHOR'      => '',
			'LABEL'       => '',
			'DESCRIPTION' => '',
		];

	}
	
	public function generateSetupForm(): Form
	{
		$fields = [];

		$this->generateSetupForm_mainFields( $fields );

		return new Form( 'module_wizard_setup_form', $fields );
	}

}