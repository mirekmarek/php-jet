<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Installer
 */
namespace Jet;

class Installer_Step_CreateSite_Controller extends Installer_Step_Controller {


	public function main() {

		if( count(Mvc_Site::getList() ) ) {
			$this->render('site-created');
			if(Http_Request::POST()->exists('go')) {
				$this->installer->goNext();
			}

			return;
		}

		$default_locale = $this->installer->getCurrentLocale();

		$session = new Session( 'create_site_session' );

		if( !$session->getValueExists('site')) {
			$site = Mvc_Factory::getSiteInstance();

			$nonSSL = 'http://'.$_SERVER['HTTP_HOST'].JET_BASE_URI;
			$SSL = 'https://'.$_SERVER['HTTP_HOST'].JET_BASE_URI;

			$site->setName('Example Site');
			$site->generateID();

			$site->addLocale( $default_locale );
			$site->addURL( $default_locale, $nonSSL );
			$site->addURL( $default_locale, $SSL );

			$session->setValue('site', $site);
		} else {
			/**
			 * @var Mvc_Site_Abstract $site
			 */
			$site = $session->getValue('site');
		}

		if(
			Http_Request::GET()->exists('create') &&
			count($site->getLocales()) &&
			$site->validateProperties()
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


		$add_locale_form = new Form('locale_add',
			array(
				Form_Factory::field('Select','locale', 'Select new locale'),
			)
		);

		$add_locale_form->getField('locale')->setSelectOptions( $avl_locales );
		$add_locale_form->getField('locale')->setIsRequired(true);

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
		$main_form_fields = array(
			Form_Factory::field('Input','name', 'Site name', $site->getName(), true),
		);

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

			$main_form_fields[] = Form_Factory::field('Input','/'.$locale.'/nonSSL', 'URL ', $nonSSL, true);
			$main_form_fields[] = Form_Factory::field('Input','/'.$locale.'/SSL', 'SSL URL ', $SSL, false);
		}

		$main_form = new Form('main', $main_form_fields );

		if(
			$main_form->catchValues() &&
			$main_form->validateValues()
		) {
			$data = $main_form->getValues();

			$site->resetID();
			$site->setName( $data['name'] );
			$site->generateID();

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
		if( count($site->getLocales()) && $site->validateProperties() ) {
			$create_form = new Form('create',
				array(
					Form_Factory::field('Select','template', 'Site template: ', '', true)
				)
			);
			$templates_list = Mvc_Site::getAvailableTemplatesList();

			$create_form->getField('template')->setSelectOptions($templates_list);

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
		return Tr::_('Create site', array(), 'CreateSite');
	}
}
