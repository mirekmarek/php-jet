<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace JetExampleApp;

use Jet\Application_Modules;
use Jet\Form;
use Jet\Form_Field_MultiSelect;
use Jet\Http_Request;
use Jet\Exception;
use Jet\Tr;

class Installer_Step_InstallModules_Controller extends Installer_Step_Controller {

	/**
	 * @var Application_Modules_Module_Manifest[]
	 */
	protected $all_modules;

	/**
	 * @var string[]
	 */
	protected $selected_modules = [];

	public function main() {
		$this->all_modules = Application_Modules::getAllModulesList(true);


        $modules_field = new Form_Field_MultiSelect('modules');
        $modules_field->setSelectOptions( $this->all_modules );
        $modules_field->setErrorMessages([
	        Form_Field_MultiSelect::ERROR_CODE_EMPTY=>'Please select module',
            Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Please select module'
        ]);

		$form = new Form('modules_select_form', [
			$modules_field
		]);

		$this->view->setVar('modules', $this->all_modules);


		if(Http_Request::POST()->exists('go')) {
			Installer::goNext();
		}

		if($form->catchValues() && $form->validateValues()) {
            $this->selected_modules = [];

            foreach($this->all_modules as $m) {
                if($m->getIsAuthController()) {
                    $this->selected_modules[] = $m->getName();
                }
            }

			$d = $form->getValues();
			$this->selected_modules = array_merge($this->selected_modules, $d['modules']);

			while( !$this->resolveDependencies() ) {}


			$result = [];

			$OK = true;

			foreach($this->selected_modules as $module_name) {
				$result[$module_name] = true;

				if($this->all_modules[$module_name]->getIsActivated()) {
					continue;
				}

				try {
					Application_Modules::installModule($module_name);
				} catch(Exception $e) {
					$result[$module_name] = $e->getMessage();

					$OK = false;
				}

				if($result[$module_name]!==true) {
					continue;
				}

				try {
					Application_Modules::activateModule($module_name);
				} catch(Exception $e) {
					$result[$module_name] = $e->getMessage();
					$OK = false;
				}

			}

			if(!$result) {
				Installer::goNext();
			}

			$this->view->setVar('result', $result);
			$this->view->setVar('OK', $OK);

			$this->render('modules-installation-result');
		} else {
			$this->view->setVar('form', $form);
			$this->render('default');
		}

	}

	public function getLabel() {
		return Tr::_('Modules installation', [], 'InstallModules');
	}

	/**
	 * @return bool
	 */
	protected function resolveDependencies( ) {
		$available_modules = [];

		foreach( $this->all_modules as $module_info ) {
			if($module_info->getIsActivated()) {
				$available_modules[] = $module_info->getName();
			}
		}

		foreach( $this->selected_modules as $module_name ) {
			$available_modules[] = $module_name;

			$module_info = $this->all_modules[$module_name];

			$require = $module_info->getRequire();

			if(!$require) {
				continue;
			}

			foreach( $require as $required_module_name ) {
				if(in_array($required_module_name, $available_modules)) {
					continue;
				}

				$position = array_search( $required_module_name, $this->selected_modules );

				if($position===null) {
					//unsolvable dependency
					continue;
				}

				unset( $this->selected_modules[$position] );

				array_unshift($this->selected_modules, $required_module_name);

				return false;
			}
		}

		return true;
	}
}
