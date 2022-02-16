<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Jet\Http_Headers;
use Jet\Locale;
use Jet\Http_Request;

/**
 *
 */
class Installer_Step_Welcome_Controller extends Installer_Step_Controller
{

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

			Http_Headers::reload( [], ['locale'] );

		}


		$this->render( 'default' );
	}

}
