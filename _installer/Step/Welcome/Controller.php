<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Jet\Locale;
use Jet\Http_Request;

/**
 *
 */
class Installer_Step_Welcome_Controller extends Installer_Step_Controller
{

	protected string $icon = 'door-open';
	
	/**
	 * @var string
	 */
	protected string $label = 'Welcome';

	/**
	 * @return bool
	 */
	public function getIsAvailable(): bool
	{
		return !Installer_Step_CreateBases_Controller::basesCreated();
	}

	/**
	 *
	 */
	public function main(): void
	{
		if(!extension_loaded( 'intl' )) {
			$this->render( 'error_intl_ext_not_installed' );
			
			return;
		}
		
		$this->catchContinue();

		$translations = [];

		foreach( Installer::getAvailableLocales() as $locale ) {
			$translations[] = $locale->toString();
		}

		if( Http_Request::GET()->exists( 'locale' ) ) {
			$locale = Http_Request::GET()->getString(
				'locale',
				Installer::getCurrentLocale()->toString(),
				$translations
			);

			$locale = new Locale( $locale );

			Installer::setSelectedLocales( [$locale] );
			Installer::setCurrentLocale( $locale );
			
			Installer::goToNext();

		}


		$this->render( 'default' );
	}

}
