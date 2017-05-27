<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Http_Request;
use Jet\Http_Headers;
use Jet\Mvc_Site;
use Jet\Mvc_Factory;
use Jet\Mvc_Site_Interface;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\IO_Dir;

/**
 *
 */
class Installer_Step_CreateSite_Controller extends Installer_Step_Controller
{

	/**
	 * @var string
	 */
	protected $label = 'Create site';

	/**
	 *
	 */
	public function main()
	{

		if( count( Mvc_Site::loadSites() ) ) {
			$this->render( 'site-created' );

			$this->catchContinue();

			return;
		}


		$default_locale = Installer::getCurrentLocale();

		$session = Installer::getSession();

		if( !$session->getValueExists( 'site' ) ) {

			$site = Mvc_Factory::getSiteInstance();

			$URL = $_SERVER['HTTP_HOST'].JET_URI_BASE;

			$site->setName( 'Example Site' );
			$site->setId(Installer::SITE_ID);

			$ld = $site->addLocale( $default_locale );
			$ld->setTitle('PHP Jet');
			$ld->setURLs( [$URL] );

			foreach( Installer::getSelectedLocales() as $locale ) {
				if( $locale->toString()==$default_locale->toString() ) {
					continue;
				}
				$ld = $site->addLocale( $locale );
				$ld->setTitle('PHP Jet');
				$ld->setURLs( [$URL.$locale->getLanguage()] );
			}

			$session->setValue( 'site', $site );
		} else {
			/**
			 * @var Mvc_Site_Interface $site
			 */
			$site = $session->getValue( 'site' );
		}


		if( Http_Request::GET()->exists( 'create' )&&count( $site->getLocales() ) ) {
			if( !$session->getValue( 'creating' ) ) {
				$session->setValue( 'creating', true );
				$this->render( 'in-progress' );

			} else {
				$site->setIsDefault( true );
				$site->setIsActive( true );

				$site->saveDataFile();


				Http_Headers::movedPermanently( '?' );
			}

		}


		//----------------------------------------------------------------------
		$main_form_fields = [];

		foreach( $site->getLocales() as $locale ) {
			$URL = $site->getLocalizedData( $locale )->getURLs()[0];

			$URL = rtrim( $URL, '/' );

			$URL_field = new Form_Field_Input( '/'.$locale.'/URL', 'URL ', $URL, true );
			$URL_field->setErrorMessages(
				[
					Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter URL',
				]
			);

			$main_form_fields[] = $URL_field;
		}

		$main_form = new Form( 'main', $main_form_fields );

		if(
			$main_form->catchInput() &&
			$main_form->validate()
		) {

			foreach( $site->getLocales() as $locale ) {
				$URL = strtolower($main_form->getField('/'.$locale.'/URL')->getValue());
				$URL = rtrim($URL, '/');

				$URL = str_replace('http://', '', $URL);
				$URL = str_replace('https://', '', $URL);
				$URL = str_replace('//', '', $URL);

				$site->getLocalizedData( $locale )->setURLs([$URL]);
			}

			Http_Headers::movedPermanently( '?create' );

		}


		//----------------------------------------------------------------------

		$this->view->setVar( 'site', $site );
		$this->view->setVar( 'main_form', $main_form );

		$this->render( 'default' );
	}

}
