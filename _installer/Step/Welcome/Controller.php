<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetExampleApp;

use Jet\Form;
use Jet\Form_Field_Select;
use Jet\Http_Headers;
use Jet\Locale;
use Jet\Mvc_Site;
use Jet\Http_Request;

/**
 *
 */
class Installer_Step_Welcome_Controller extends Installer_Step_Controller {

	/**
	 * @var string
	 */
	protected $label = 'Welcome';

	/**
	 * @return bool
	 */
	public function getIsAvailable()
	{
		return count(Mvc_Site::getList() )==0;
	}

	/**
	 *
	 */
	public function main() {
		if(Http_Request::POST()->exists('go')) {
			Installer::goToNext();
		}

		$translations = [];

		foreach(Installer::getAvailableLocales() as $locale ) {
			$translations[$locale->toString()] = $locale->getName($locale);
		}

        $locale_field = new Form_Field_Select('locale', 'Please select locale: ');
        $locale_field->setSelectOptions( $translations );
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

			$locale = new Locale($locale_field->getValue());

			Installer::setSelectedLocales([$locale]);
			Installer::setCurrentLocale($locale);

			Http_Headers::reload();
		}

		//Installer::goToNext();

		$this->view->setVar('form', $select_locale_form);

		$this->render('default');
	}

}
