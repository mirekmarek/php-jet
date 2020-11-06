<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Form;
use Jet\Form_Field_Checkbox;

/**
 *
 */
class Installer_Step_SelectLocales_Controller extends Installer_Step_Controller
{

	/**
	 * @var string
	 */
	protected $label = 'Select Locales';

	/**
	 * @return bool
	 */
	public function getIsAvailable()
	{
		return !Installer_Step_CreateSite_Controller::sitesCreated();
	}

	/**
	 *
	 */
	public function main()
	{

		$locale_fields = [];

		$selected_locales = Installer::getSelectedLocales();

		foreach( Installer::getAvailableLocales() as $locale ) {

			if( ((string)$locale)!=((string)Installer::getCurrentLocale()) ) {
				continue;
			}

			$field = new Form_Field_Checkbox( 'locale_'.$locale, $locale->getName( $locale ) );
			$field->setDefaultValue( isset( $selected_locales[$locale->toString()] ) );
			$field->setIsReadonly( true );

			$locale_fields[] = $field;
		}

		foreach( Installer::getAvailableLocales() as $locale ) {

			if( ((string)$locale)==((string)Installer::getCurrentLocale()) ) {
				continue;
			}

			$field = new Form_Field_Checkbox( 'locale_'.$locale, $locale->getName( $locale ) );
			$field->setDefaultValue( isset( $selected_locales[$locale->toString()] ) );

			$locale_fields[] = $field;
		}


		$select_locale_form = new Form( 'select_locale_form', $locale_fields );

		$select_locale_form->setDoNotTranslateTexts(true);


		if( $select_locale_form->catchInput()&&$select_locale_form->validate() ) {
			$selected_locales = [];

			foreach( Installer::getAvailableLocales() as $locale ) {
				if( ((string)$locale)==((string)Installer::getCurrentLocale()) ) {
					$selected_locales[] = $locale;

					continue;
				}

				$field = $select_locale_form->field( 'locale_'.$locale );
				if( $field->getValue() ) {
					$selected_locales[] = $locale;
				}
			}

			Installer::setSelectedLocales( $selected_locales );

			Installer::getSession()->unsetValue( 'sites' );

			Installer::goToNext();
		}


		$this->view->setVar( 'form', $select_locale_form );

		$this->render( 'default' );
	}

}
