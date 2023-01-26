<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Jet\Application_Modules;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_MultiSelect;
use Jet\Exception;

/**
 *
 */
class Installer_Step_InstallModules_Controller extends Installer_Step_Controller
{
	protected string $icon = 'boxes-stacked';
	
	/**
	 * @var string
	 */
	protected string $label = 'Modules installation';


	public function main(): void
	{
		$all_modules = Application_Modules::allModulesList();
		$modules_scope = [];
		foreach($all_modules as $module) {
			$modules_scope[$module->getName()] = $module->getLabel();
		}


		$modules_field = new Form_Field_MultiSelect( 'modules' );
		$modules_field->setSelectOptions( $modules_scope );
		$modules_field->setErrorMessages(
			[
				Form_Field::ERROR_CODE_EMPTY         => 'Please select module',
				Form_Field::ERROR_CODE_INVALID_VALUE => 'Please select module',
			]
		);

		$form = new Form(
			'modules_select_form', [$modules_field,]
		);

		$this->view->setVar( 'modules', $all_modules );


		$this->catchContinue();

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$selected_modules = $modules_field->getValue();

			foreach( $all_modules as $m ) {
				if(
					$m->isMandatory() &&
					!$m->isInstalled() &&
					!in_array($m->getName(), $selected_modules)
				) {
					$selected_modules[] = $m->getName();
				}
			}



			$result = [];

			$OK = true;

			foreach( $selected_modules as $module_name ) {
				$result[$module_name] = true;

				if( $all_modules[$module_name]->isActivated() ) {
					continue;
				}

				try {
					Application_Modules::installModule( $module_name );
				} catch( Exception $e ) {
					$result[$module_name] = $e->getMessage();

					$OK = false;
				}

				if( $result[$module_name] !== true ) {
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
