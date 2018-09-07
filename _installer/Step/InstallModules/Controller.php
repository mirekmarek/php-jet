<?php
/**
 *
 * @copyright Copyright (c) 2011-2018 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Application_Modules;
use Jet\Application_Module_Manifest;
use Jet\Form;
use Jet\Form_Field_MultiSelect;
use Jet\Exception;

/**
 *
 */
class Installer_Step_InstallModules_Controller extends Installer_Step_Controller
{

	/**
	 * @var string
	 */
	protected $label = 'Modules installation';

	/**
	 * @var Application_Module_Manifest[]
	 */
	protected $all_modules;

	/**
	 * @var string[]
	 */
	protected $selected_modules = [];

	public function main()
	{
		$this->all_modules = Application_Modules::allModulesList();


		$modules_field = new Form_Field_MultiSelect( 'modules' );
		$modules_field->setSelectOptions( $this->all_modules );
		$modules_field->setErrorMessages(
			[
				Form_Field_MultiSelect::ERROR_CODE_EMPTY         => 'Please select module',
				Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE => 'Please select module',
			]
		);

		$form = new Form(
			'modules_select_form', [ $modules_field, ]
		);

		$this->view->setVar( 'modules', $this->all_modules );


		$this->catchContinue();

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$this->selected_modules = [];

			foreach( $this->all_modules as $m ) {
				if( $m->isMandatory() ) {
					$this->selected_modules[] = $m->getName();
				}
			}


			$this->selected_modules = array_merge( $this->selected_modules, $modules_field->getValue() );

			$result = [];

			$OK = true;

			foreach( $this->selected_modules as $module_name ) {
				$result[$module_name] = true;

				if( $this->all_modules[$module_name]->isActivated() ) {
					continue;
				}

				try {
					Application_Modules::installModule( $module_name );
				} catch( Exception $e ) {
					$result[$module_name] = $e->getMessage();

					$OK = false;
				}

				if( $result[$module_name]!==true ) {
					continue;
				}

				try {
					Application_Modules::activateModule( $module_name );
				} catch( Exception $e ) {
					$result[$module_name] = $e->getMessage();
					$OK = false;
				}

			}

			if( !$result ) {
				Installer::goToNext();
			}

			$this->view->setVar( 'result', $result );
			$this->view->setVar( 'OK', $OK );

			$this->render( 'modules-installation-result' );
		} else {
			$this->view->setVar( 'form', $form );
			$this->render( 'default' );
		}

	}
}
