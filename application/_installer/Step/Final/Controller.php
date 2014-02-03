<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace Jet;

class Installer_Step_Final_Controller extends Installer_Step_Controller {


	public function main() {
		try {
			(new Javascript_Lib_Dojo_Config(true))->save();
		} catch( IO_File_Exception $e ) {
			if($e->getCode()!=IO_File_Exception::CODE_CHMOD_FAILED) {
				throw $e;
			}
		}
		try {
			(new Javascript_Lib_Jet_Config(true))->save();
		} catch( IO_File_Exception $e ) {
			if($e->getCode()!=IO_File_Exception::CODE_CHMOD_FAILED) {
				throw $e;
			}
		}
		try {
			(new Javascript_Lib_TinyMCE_Config(true))->save();
		} catch( IO_File_Exception $e ) {
			if($e->getCode()!=IO_File_Exception::CODE_CHMOD_FAILED) {
				throw $e;
			}
		}


		$cp_conf_source = $this->installer->getTmpConfigFilePath();
		$cp_conf_target = JET_CONFIG_PATH.JET_APPLICATION_CONFIGURATION_NAME.'.php';

		$copy_OK = true;
		$copy_message = '';
		try {
			IO_File::copy($cp_conf_source, $cp_conf_target);
		} catch(IO_File_Exception $e) {
			$copy_OK = false;
			$copy_message = $e->getMessage();
		}

		if($copy_OK) {
			$this->render('default');
		} else {
			$this->view->setVar('message', $copy_message);
			$this->view->setVar('source', $cp_conf_source);
			$this->view->setVar('target', $cp_conf_target);

			$this->render('default-copy-config');
		}



	}

	public function getLabel() {
		return Tr::_('Done', array(), 'Final');
	}
}
