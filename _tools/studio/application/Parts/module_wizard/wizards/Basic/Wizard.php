<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetStudio\ModuleWizard\Basic;

use Jet\Form;
use JetStudio\ModuleWizard;
use JetStudio\ModuleWizards;

/**
 *
 */
class Wizard extends ModuleWizard {

	/**
	 * @var string
	 */
	protected $title = 'Basic module';

	/**
	 * @var string
	 */
	protected $description = 'Create basic module without any extra features. Only very basic skeleton.';

	/**
	 *
	 */
	public function init()
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
	public function generateSetupForm()
	{
		$fields = [];

		$this->generateSetupForm_mainFields( $fields );

		$form = new Form('module_wizard_setup_form', $fields);

		return $form;
	}

}