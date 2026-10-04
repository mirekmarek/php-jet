<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\Locale;

/**
 *
 */
class Installer_Step_ConfigURLs_Controller extends Installer_Step_Controller
{
	protected string $icon = 'globe';

	/**
	 * @var string
	 */
	protected string $label = 'Config URLs';


	/**
	 *
	 */
	public function main(): void
	{

		$default_locale = Installer::getCurrentLocale();

		$bases = Installer::getBases();


		//----------------------------------------------------------------------
		$main_form_fields = [];

		foreach( $bases as $base ) {
			foreach( $base->getLocales() as $locale ) {
				/**
				 * @var Locale $locale
				 */
				$URL = $base->getLocalizedData( $locale )->getURLs()[0];

				$URL = rtrim( $URL, '/' );

				$URL_field = new Form_Field_Input( '/' . $base->getId() . '/' . $locale . '/URL', 'URL ' );
				$URL_field->setDefaultValue($URL);
				$URL_field->setIsRequired(true);

				$URL_field->setErrorMessages(
					[
						Form_Field::ERROR_CODE_EMPTY => 'Please enter URL',
					]
				);

				$main_form_fields[] = $URL_field;
			}

		}


		$main_form = new Form( 'main', $main_form_fields );

		if(
			$main_form->catchInput() &&
			$main_form->validate()
		) {
			foreach( $bases as $base ) {

				foreach( $base->getLocales() as $locale ) {
					/**
					 * @var Locale $locale
					 */
					$URL = strtolower( $main_form->getField( '/' . $base->getId() . '/' . $locale . '/URL' )->getValue() );
					$URL = rtrim( $URL, '/' );
					
					/** @noinspection HttpUrlsUsage */
					$URL = str_replace( 'http://', '', $URL );
					$URL = str_replace( 'https://', '', $URL );
					$URL = str_replace( '//', '', $URL );

					$base->getLocalizedData( $locale )->setURLs( [$URL] );
				}
			}
			
			Installer::goToNext();
		}


		//----------------------------------------------------------------------

		$this->view->setVar( 'bases', $bases );
		$this->view->setVar( 'main_form', $main_form );

		$this->render( 'default' );
	}

}
