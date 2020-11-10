<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
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
use Jet\Mvc_Site_LocalizedData_MetaTag;
use Jet\Tr;
use Jet\UI_messages;
use Jet\SysConf_URI;

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
	 * @return bool
	 */
	public static function sitesCreated()
	{
		return count( Mvc_Site::loadSites() )==3;
	}

	/**
	 *
	 */
	public function main()
	{

		if( static::sitesCreated() ) {
			$this->render( 'site-created' );

			$this->catchContinue();

			return;
		}

		$default_locale = Installer::getCurrentLocale();

		$session = Installer::getSession();

		if( !$session->getValueExists( 'sites' ) )
		{

			$URL = $_SERVER['HTTP_HOST'].SysConf_URI::BASE();

			$web = Mvc_Factory::getSiteInstance();
			$web->setName( 'Example Web' );
			$web->setId( Application_Web::getSiteId() );

			$ld = $web->addLocale( $default_locale );
			$ld->setTitle( Tr::_( 'PHP Jet Example Web', [], null, $default_locale ) );
			$ld->setURLs( [$URL] );

			$meta_tag = new Mvc_Site_LocalizedData_MetaTag();
			$meta_tag->setAttribute( 'attribute' );
			$meta_tag->setAttributeValue( 'example' );
			$meta_tag->setContent( 'Example tag' );

			$ld->addDefaultMetaTag( $meta_tag );

			foreach( Installer::getSelectedLocales() as $locale ) {
				if( $locale->toString()==$default_locale->toString() ) {
					continue;
				}
				$ld = $web->addLocale( $locale );
				$ld->setTitle( Tr::_( 'PHP Jet Example Web', [], null, $locale ) );
				$ld->setURLs( [$URL.$locale->getLanguage()] );
				$ld->addDefaultMetaTag( $meta_tag );
			}
			$web->setIsDefault( true );
			$web->setIsActive( true );
			$web->setInitializer(['JetApplication\Application_Web','init']);





			$admin = Mvc_Factory::getSiteInstance();
			$admin->setIsSecret(true);
			$admin->setName( 'Example Administration' );
			$admin->setId( Application_Admin::getSiteId() );

			$ld = $admin->addLocale( $default_locale );
			$ld->setTitle( Tr::_( 'PHP Jet Example Administration', [], null, $default_locale ) );
			$ld->setURLs( [$URL.'admin/'] );

			foreach( Installer::getSelectedLocales() as $locale ) {
				if( $locale->toString()==$default_locale->toString() ) {
					continue;
				}
				$ld = $admin->addLocale( $locale );
				$ld->setTitle( Tr::_( 'PHP Jet Example Administration', [], null, $locale ) );
				$ld->setURLs( [$URL.'admin/'.$locale->getLanguage().'/'] );
			}
			$admin->setIsActive( true );
			$admin->setInitializer(['JetApplication\Application_Admin','init']);



			$rest = Mvc_Factory::getSiteInstance();
			$rest->setIsSecret(true);
			$rest->setName( 'Example REST API' );
			$rest->setId( Application_REST::getSiteId() );

			$ld = $rest->addLocale( $default_locale );
			$ld->setTitle( Tr::_( 'PHP Jet Example REST API', [], null, $default_locale ) );
			$ld->setURLs( [$URL.'rest/'] );

			foreach( Installer::getSelectedLocales() as $locale ) {
				if( $locale->toString()==$default_locale->toString() ) {
					continue;
				}
				$ld = $rest->addLocale( $locale );
				$ld->setTitle( Tr::_( 'PHP Jet Example REST API', [], null, $locale ) );
				$ld->setURLs( [$URL.'rest/'.$locale->getLanguage().'/'] );
			}
			$rest->setIsActive( true );
			$rest->setInitializer(['JetApplication\Application_REST','init']);



			$sites = [
				$web->getId()   => $web,
			    $admin->getId() => $admin,
			    $rest->getId()  => $rest
			];


			$session->setValue( 'sites', $sites );

		}
		else {
			$sites = $session->getValue( 'sites' );
		}

		/**
		 * @var Mvc_Site_Interface $site
		 */


		if(
			Http_Request::GET()->exists( 'create' ) &&
			count( $sites )
		) {
			if( !$session->getValue( 'creating' ) ) {
				$session->setValue( 'creating', true );
				$this->render( 'in-progress' );

			} else {
				/**
				 * @var Mvc_Site[] $sites
				 */
				$sites[Application_REST::getSiteId()]->setInitializer(['JetApplication\Application_REST','init']);
				$sites[Application_REST::getSiteId()]->setIsSecret(true);

				$sites[Application_Admin::getSiteId()]->setInitializer(['JetApplication\Application_Admin','init']);
				$sites[Application_Admin::getSiteId()]->setIsSecret(true);

				$sites[Application_Web::getSiteId()]->setInitializer(['JetApplication\Application_Web','init']);

				try {
					foreach( $sites as $site ) {
						$site->saveDataFile();
					}

				} catch( \Exception $e ) {
					UI_messages::danger( Tr::_('Something went wrong: %error%', ['error'=>$e->getMessage()]) );
				}



				Http_Headers::movedPermanently( '?' );
			}

		}


		//----------------------------------------------------------------------
		$main_form_fields = [];

		foreach( $sites as $site) {
			foreach( $site->getLocales() as $locale ) {
				$URL = $site->getLocalizedData( $locale )->getURLs()[0];

				$URL = rtrim( $URL, '/' );

				$URL_field = new Form_Field_Input( '/'.$site->getId().'/'.$locale.'/URL', 'URL ', $URL, true );

				$URL_field->setErrorMessages(
					[
						Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter URL',
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

			foreach( $sites as $site ) {

				foreach( $site->getLocales() as $locale ) {
					$URL = strtolower($main_form->getField('/'.$site->getId().'/'.$locale.'/URL')->getValue());
					$URL = rtrim($URL, '/');

					$URL = str_replace('http://', '', $URL);
					$URL = str_replace('https://', '', $URL);
					$URL = str_replace('//', '', $URL);

					$site->getLocalizedData( $locale )->setURLs([$URL]);
				}
			}


			Http_Headers::movedPermanently( '?create' );

		}


		//----------------------------------------------------------------------

		$this->view->setVar( 'sites', $sites );
		$this->view->setVar( 'main_form', $main_form );

		$this->render( 'default' );
	}

}
