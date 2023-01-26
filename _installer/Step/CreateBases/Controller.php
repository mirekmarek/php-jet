<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Exception;
use Jet\Http_Request;
use Jet\Http_Headers;
use Jet\MVC;
use Jet\MVC_Base;
use Jet\Factory_MVC;
use Jet\MVC_Base_Interface;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\MVC_Base_LocalizedData_MetaTag;
use Jet\Tr;
use Jet\Translator;
use Jet\UI_messages;
use Jet\SysConf_URI;

use JetApplication\Application_Admin;
use JetApplication\Application_Web;
use JetApplication\Application_REST;

/**
 *
 */
class Installer_Step_CreateBases_Controller extends Installer_Step_Controller
{
	protected string $icon = 'wand-sparkles';

	/**
	 * @var string
	 */
	protected string $label = 'Create bases';

	/**
	 * @return bool
	 */
	public static function basesCreated() : bool
	{
		return count( MVC::getBases() ) == 3;
	}

	/**
	 *
	 */
	public function main(): void
	{

		if( static::basesCreated() ) {
			$this->render( 'base-created' );

			$this->catchContinue();

			return;
		}

		$default_locale = Installer::getCurrentLocale();

		$session = Installer::getSession();

		if( !$session->getValueExists( 'bases' ) ) {

			$URL = $_SERVER['HTTP_HOST'] . SysConf_URI::getBase();

			$web = Factory_MVC::getBaseInstance();
			$web->setName( 'Example Web' );
			$web->setId( Application_Web::getBaseId() );

			$ld = $web->addLocale( $default_locale );
			$ld->setTitle( Tr::_( 'PHP Jet Example Web', [], null, $default_locale ) );
			$ld->setURLs( [$URL] );

			$meta_tag = new MVC_Base_LocalizedData_MetaTag();
			$meta_tag->setAttribute( 'attribute' );
			$meta_tag->setAttributeValue( 'example' );
			$meta_tag->setContent( 'Example tag' );

			$ld->addDefaultMetaTag( $meta_tag );

			foreach( Installer::getSelectedLocales() as $locale ) {
				if( $locale->toString() == $default_locale->toString() ) {
					continue;
				}
				$ld = $web->addLocale( $locale );
				$ld->setTitle( Tr::_( 'PHP Jet Example Web', [], null, $locale ) );
				$ld->setURLs( [$URL . $locale->getLanguage()] );
				$ld->addDefaultMetaTag( $meta_tag );
			}
			$web->setIsDefault( true );
			$web->setIsActive( true );
			$web->setInitializer( [
				Application_Web::class,
				'init'
			] );


			$admin = Factory_MVC::getBaseInstance();
			$admin->setIsSecret( true );
			$admin->setName( 'Example Administration' );
			$admin->setId( Application_Admin::getBaseId() );

			$ld = $admin->addLocale( $default_locale );
			$ld->setTitle( Tr::_( 'PHP Jet Example Administration', [], null, $default_locale ) );
			$ld->setURLs( [$URL . 'admin/'] );

			foreach( Installer::getSelectedLocales() as $locale ) {
				if( $locale->toString() == $default_locale->toString() ) {
					continue;
				}
				$ld = $admin->addLocale( $locale );
				$ld->setTitle( Tr::_( 'PHP Jet Example Administration', [], null, $locale ) );
				$ld->setURLs( [$URL . 'admin/' . $locale->getLanguage() . '/'] );
			}
			$admin->setIsActive( true );
			$admin->setInitializer( [
				Application_Admin::class,
				'init'
			] );


			$rest = Factory_MVC::getBaseInstance();
			$rest->setIsSecret( true );
			$rest->setName( 'Example REST API' );
			$rest->setId( Application_REST::getBaseId() );

			$ld = $rest->addLocale( $default_locale );
			$ld->setTitle( Tr::_( 'PHP Jet Example REST API', [], null, $default_locale ) );
			$ld->setURLs( [$URL . 'rest/'] );

			foreach( Installer::getSelectedLocales() as $locale ) {
				if( $locale->toString() == $default_locale->toString() ) {
					continue;
				}
				$ld = $rest->addLocale( $locale );
				$ld->setTitle( Tr::_( 'PHP Jet Example REST API', [], null, $locale ) );
				$ld->setURLs( [$URL . 'rest/' . $locale->getLanguage() . '/'] );
			}
			$rest->setIsActive( true );
			$rest->setInitializer( [
				Application_REST::class,
				'init'
			] );


			$bases = [
				$web->getId() => $web,
				$admin->getId() => $admin,
				$rest->getId() => $rest
			];


			$session->setValue( 'bases', $bases );

		} else {
			$bases = $session->getValue( 'bases' );
		}

		/**
		 * @var MVC_Base_Interface $base
		 */


		if(
			Http_Request::GET()->exists( 'create' ) &&
			count( $bases )
		) {
			if( !$session->getValue( 'creating' ) ) {
				$session->setValue( 'creating', true );
				$this->render( 'in-progress' );

			} else {
				/**
				 * @var MVC_Base[] $bases
				 */
				$bases[Application_REST::getBaseId()]->setInitializer( [
					Application_REST::class,
					'init'
				] );
				$bases[Application_REST::getBaseId()]->setIsSecret( true );

				$bases[Application_Admin::getBaseId()]->setInitializer( [
					Application_Admin::class,
					'init'
				] );
				$bases[Application_Admin::getBaseId()]->setIsSecret( true );

				$bases[Application_Web::getBaseId()]->setInitializer( [
					Application_Web::class,
					'init'
				] );

				try {
					foreach( $bases as $base ) {
						$base->saveDataFile();
					}

				} catch( Exception $e ) {
					UI_messages::danger( Tr::_( 'Something went wrong: %error%', ['error' => $e->getMessage()], Translator::COMMON_DICTIONARY ) );
				}


				Http_Headers::movedPermanently( '?' );
			}

		}


		//----------------------------------------------------------------------
		$main_form_fields = [];

		foreach( $bases as $base ) {
			foreach( $base->getLocales() as $locale ) {
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
					$URL = strtolower( $main_form->getField( '/' . $base->getId() . '/' . $locale . '/URL' )->getValue() );
					$URL = rtrim( $URL, '/' );

					$URL = str_replace( 'http://', '', $URL );
					$URL = str_replace( 'https://', '', $URL );
					$URL = str_replace( '//', '', $URL );

					$base->getLocalizedData( $locale )->setURLs( [$URL] );
				}
			}


			Http_Headers::movedPermanently( '?create' );

		}


		//----------------------------------------------------------------------

		$this->view->setVar( 'bases', $bases );
		$this->view->setVar( 'main_form', $main_form );

		$this->render( 'default' );
	}

}
