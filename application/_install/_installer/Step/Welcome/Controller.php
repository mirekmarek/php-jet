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

use Jet\Form;
use Jet\Form_Field_Select;
use Jet\Locale;
use Jet\Tr;

class Installer_Step_Welcome_Controller extends Installer_Step_Controller {



	public function main() {

        $locale_field = new Form_Field_Select('locale', 'Please select locale: ');
        $locale_field->setSelectOptions( Installer::getTranslations() );
        $locale_field->setIsRequired(true);
        $locale_field->setDefaultValue(Installer::getCurrentLocale());
        $locale_field->setErrorMessages([
	        Form_Field_Select::ERROR_CODE_INVALID_VALUE=>'Please select locale',
	        Form_Field_Select::ERROR_CODE_EMPTY=>'Please select locale'
        ]);

		$select_locale_form = new Form('select_locale_form',
			[
				$locale_field,
			]
		);



		if($select_locale_form->catchValues() && $select_locale_form->validateValues()) {
			$d = $select_locale_form->getValues();
			Installer::setCurrentLocale(new Locale($d['locale']));
			Installer::goNext();
		}

		$this->view->setVar('form', $select_locale_form);

		$this->render('default');
	}

	public function getLabel() {
		return Tr::_('Welcome', [], 'Welcome');
	}
}
