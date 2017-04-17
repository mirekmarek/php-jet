<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace JetExampleApp;

use Jet\Http_Request;
use Jet\Http_Headers;
use Jet\Session;
use Jet\Mvc_Site;
use Jet\Mvc_Factory;
use Jet\Mvc_Site_Interface;
use Jet\Locale;
use Jet\Form;
use Jet\Form_Field_Select;
use Jet\Form_Field_Input;
use Jet\Tr;

class Installer_Step_CreateSite_Controller extends Installer_Step_Controller {


	public function main() {

		if( count(Mvc_Site::getList() ) ) {
			$this->render('site-created');
			if(Http_Request::POST()->exists('go')) {
				Installer::goNext();
			}

			return;
		}


		$default_locale = Installer::getCurrentLocale();

		$session = new Session( 'create_site_session' );

		if( !$session->getValueExists('site')) {
			$site = Mvc_Factory::getSiteInstance();

			$nonSSL = 'http://'.$_SERVER['HTTP_HOST'].JET_BASE_URI;
			$SSL = 'https://'.$_SERVER['HTTP_HOST'].JET_BASE_URI;

			$site->setName('Example Site');
			$site->generateSiteId();

			$site->addLocale( $default_locale );
			$site->addURL( $default_locale, $nonSSL );
			$site->addURL( $default_locale, $SSL );

			$session->setValue('site', $site);
		} else {
			/**
			 * @var Mvc_Site_Interface $site
			 */
			$site = $session->getValue('site');
		}

		if(
			Http_Request::GET()->exists('create') &&
			count($site->getLocales())
		) {
			if(!$session->getValue('creating')) {
				$session->setValue('creating', true);
				$this->render('in-progress');

			} else {
                $site->setIsDefault(true);
                $site->setIsActive(true);

                //ob_start();
                $site->create( $session->getValue('template') );
                //ob_end_clean();

				Http_Headers::movedPermanently('?');
			}

		}

		//----------------------------------------------------------------------

		$all_locales = Locale::getAllLocalesList($default_locale);
		$avl_locales = $all_locales;

		foreach( $site->getLocales() as $s_locale) {
			unset($avl_locales[(string)$s_locale]);
		}


        $locale_field = new Form_Field_Select('locale', 'Select new locale');
        $locale_field->setSelectOptions( $avl_locales );
        $locale_field->setIsRequired(true);
        $locale_field->setErrorMessages([
            Form_Field_Select::ERROR_CODE_EMPTY=>'Please select locale',
            Form_Field_Select::ERROR_CODE_INVALID_VALUE=>'Please select locale'
        ]);

		$add_locale_form = new Form('locale_add',
			[
                $locale_field
			]
		);


		if($add_locale_form->catchValues() && $add_locale_form->validateValues()) {
			$d = $add_locale_form->getValues();
			$locale = $d['locale'];

			$nonSSL = 'http://'.$_SERVER['HTTP_HOST'].JET_BASE_URI.$locale.'/';
			$SSL = 'https://'.$_SERVER['HTTP_HOST'].JET_BASE_URI.$locale.'/';

			$locale = new Locale($locale);

			$site->addLocale( $locale );
			$site->addURL( $locale, $nonSSL );
			$site->addURL( $locale, $SSL );

			Http_Headers::formSent($add_locale_form);
		}

		if(Http_Request::GET()->exists('remove_locale')) {
			$remove_locale = Http_Request::GET()->getString('remove_locale');

			$site->removeLocale( new Locale($remove_locale) );

			Http_Headers::movedPermanently('?');
		}



		//----------------------------------------------------------------------
        $name_field = new Form_Field_Input('name', 'Site name', $site->getName(), true);
        $name_field->setErrorMessages([
            Form_Field_Input::ERROR_CODE_EMPTY => 'Please specify site name'
        ]);
		$main_form_fields = [
            $name_field,
		];

		foreach($site->getLocales() as $locale ) {
			$URLs = $site->getLocalizedData($locale)->getURLs();

			$nonSSL = "";
			$SSL = "";

			foreach( $URLs as $URL ) {
				if($URL->getIsSSL()) {
					$SSL = $URL->getURL();
				} else {
					$nonSSL = $URL->getURL();
				}
			}

            $nonSSL_URL_field = new Form_Field_Input('/'.$locale.'/nonSSL', 'URL ', $nonSSL, true);
            $nonSSL_URL_field->setErrorMessages([
                Form_Field_Input::ERROR_CODE_EMPTY => 'Please specify URL'
            ]);
            $SSL_URL_field = new Form_Field_Input('/'.$locale.'/SSL', 'SSL URL ', $SSL, false);
            $SSL_URL_field->setErrorMessages([
                Form_Field_Input::ERROR_CODE_EMPTY => 'Please specify URL'
            ]);

			$main_form_fields[] = $nonSSL_URL_field;
			$main_form_fields[] = $SSL_URL_field;
		}

		$main_form = new Form('main', $main_form_fields );

		if(
			$main_form->catchValues() &&
			$main_form->validateValues()
		) {
			$data = $main_form->getValues();

			$site->setName( $data['name'] );
			$site->generateSiteId();

			foreach( $site->getLocales() as $locale ) {
				foreach( $site->getLocalizedData($locale)->getURLs() as $URL ) {
					if($URL->getIsSSL()) {
						$URL->setURL($data['/'.$locale.'/SSL']);
					} else {
						$URL->setURL($data['/'.$locale.'/nonSSL']);
					}
				}
			}
		}

		//----------------------------------------------------------------------
		if( count($site->getLocales()) ) {
            $templates_list = Mvc_Site::getAvailableTemplatesList();

            $select_template_field = new Form_Field_Select('template', 'Site template: ', '', true);
            $select_template_field->setSelectOptions($templates_list);
            $select_template_field->setErrorMessages([
                Form_Field_Select::ERROR_CODE_EMPTY => 'Please select site template',
                Form_Field_Select::ERROR_CODE_INVALID_VALUE => 'Please select site template'
            ]);

			$create_form = new Form('create',
				[
                    $select_template_field
				]
			);

			$this->view->setVar('create_form', $create_form);

			if(
				$create_form->catchValues() &&
				$create_form->validateValues()
			) {
				$session->setValue('template', $create_form->getField('template')->getValue() );
				Http_Headers::movedPermanently('?create');
			}
		}

		//----------------------------------------------------------------------

		$this->view->setVar('current_locale', $default_locale);
		$this->view->setVar('site', $site );
		$this->view->setVar('add_locale_form', $add_locale_form);
		$this->view->setVar('main_form', $main_form);

		$this->render('default');
	}

	public function getLabel() {
		return Tr::_('Create site', [], 'CreateSite');
	}
}
