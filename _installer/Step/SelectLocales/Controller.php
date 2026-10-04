<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Jet\Form;
use Jet\Form_Field_Hidden;
use Jet\Locale;

/**
 *
 */
class Installer_Step_SelectLocales_Controller extends Installer_Step_Controller
{
	protected string $icon = 'language';
	
	/**
	 * @var string
	 */
	protected string $label = 'Select locales';

	/**
	 * @return bool
	 */
	public function getIsAvailable(): bool
	{
		return true;
	}

	/**
	 *
	 */
	public function main(): void
	{

		$locale_fields = [];

		$selected_locales = Installer::getSelectedLocales();


		$selected_locales_field = new Form_Field_Hidden('selected_locales');
		$selected_locales_field->setDefaultValue( implode(',', array_keys( $selected_locales )) );
		$selected_locales_field->setFieldValueCatcher( function( string $val ) {
			$val = explode( ',', $val );
			$selected_locales = [];
			
			foreach( Installer::getAvailableLocales() as $locale_code=> $locale_name ) {
				if(in_array($locale_code, $val)) {
					$selected_locales[] = new Locale( $locale_code );
				}
			}
			
			Installer::setSelectedLocales( $selected_locales );
		} );

		$select_locale_form = new Form( 'select_locale_form', [$selected_locales_field] );


		if( $select_locale_form->catch() ) {
			if(count(Installer::getSelectedLocales())) {
				Installer::initBases();
				Installer::goToNext();
			}
		}
		
		$this->view->setVar( 'form', $select_locale_form );
		$this->view->setVar('selected_locales', array_keys($selected_locales));

		$this->render( 'default' );
	}

}
