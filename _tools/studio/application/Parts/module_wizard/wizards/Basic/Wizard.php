<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetStudio\ModuleWizard\Basic;

use Jet\Form;
use JetStudio\ModuleWizard;

/**
 *
 */
class Wizard extends ModuleWizard
{

	/**
	 * @var string
	 */
	protected string $title = 'Basic module';

	/**
	 * @var string
	 */
	protected string $description = 'Create basic module without any extra features. Only very basic skeleton.';

	/**
	 *
	 */
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

	/**
	 * @return Form
	 */
	public function generateSetupForm(): Form
	{
		$fields = [];

		$this->generateSetupForm_mainFields( $fields );

		return new Form( 'module_wizard_setup_form', $fields );
	}

}