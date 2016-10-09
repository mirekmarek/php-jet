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
namespace Jet;

class Installer_Step_Welcome_Controller extends Installer_Step_Controller {



	public function main() {
		$_translations = file(JET_APPLICATION_PATH.'_install/_installer/available translations.txt');

		//$all_translations = Locale::getAllLocalesList($this->installer->getCurrentLocale());
		$translations = [];
		foreach($_translations as $tr) {
			$tr = trim($tr);
			list($tr,$lng) = explode(':', $tr);

			$translations[$tr] = $lng;
		}

        $locale_field = new Form_Field_Select('locale', 'Please select locale: ');
        $locale_field->setSelectOptions( $translations );
        $locale_field->setIsRequired(true);
        $locale_field->setDefaultValue($this->installer->getCurrentLocale());
        $locale_field->setErrorMessages([
            Form_Field_MultiSelect::ERROR_CODE_INVALID_VALUE=>'Please select locale',
            Form_Field_Abstract::ERROR_CODE_EMPTY=>'Please select locale'
        ]);

		$select_locale_form = new Form('select_locale_form',
			[
				$locale_field,
			]
		);



		if($select_locale_form->catchValues() && $select_locale_form->validateValues()) {
			$d = $select_locale_form->getValues();
			$this->installer->setCurrentLocale(new Locale($d['locale']));
			$this->installer->goNext();
		}

		$this->view->setVar('form', $select_locale_form);

		$this->render('default');
	}

	public function getLabel() {
		return Tr::_('Welcome', [], 'Welcome');
	}
}
