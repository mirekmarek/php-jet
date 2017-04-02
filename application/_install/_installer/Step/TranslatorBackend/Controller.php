<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace JetExampleApp;

use Jet\Translator_Config;
use Jet\Translator;
use Jet\IO_Dir;
use Jet\Locale;
use Jet\Tr;

class Installer_Step_TranslatorBackend_Controller extends Installer_Step_Controller {


	public function main() {
		$main_config = new Translator_Config(true);

		$config = $main_config->getBackendConfig(true);
		$form = $config->getCommonForm();

		if( $config->catchForm($form) ) {
			$config->save();

			Translator::helper_create();


			$dictionaries_path = JET_APPLICATION_PATH."_install/dictionaries/";

			$list = IO_Dir::getList( $dictionaries_path, '*.php' );

			$backend = Translator::getBackendInstance();

			foreach( $list as $path=>$file_name ) {
				list($locale) = explode('.', $file_name);
				$locale = new Locale($locale);

				$dictionary = $backend->loadDictionary( Tr::COMMON_NAMESPACE, $locale, $path );

				$backend->saveDictionary( $dictionary );
			}


			$this->installer->goNext();
		}

		$this->view->setVar('form', $form);
		$this->view->setVar('config', $config);
		$this->render('default');
	}

	public function getLabel() {
		return Tr::_('Backend configuration', [], 'TranslatorBackend');
	}

	public function getIsSubStep() {
		return true;
	}
}
