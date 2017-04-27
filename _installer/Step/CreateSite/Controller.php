<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetExampleApp;

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
class Installer_Step_CreateSite_Controller extends Installer_Step_Controller {

	/**
	 * @var string
	 */
	protected $label = 'Create site';

	/**
	 *
	 */
	public function main() {

		if( count(Mvc_Site::getList() ) ) {
			$this->render('site-created');
			if(Http_Request::POST()->exists('go')) {
				Installer::goToNext();
			}

			return;
		}


		$default_locale = Installer::getCurrentLocale();

		$session = Installer::getSession();

		if( !$session->getValueExists('site')) {

			$site = Mvc_Factory::getSiteInstance();

			$nonSSL = 'http://'.$_SERVER['HTTP_HOST'].JET_BASE_URI;
			$SSL = 'https://'.$_SERVER['HTTP_HOST'].JET_BASE_URI;

			$site->setName('Example Site');
			$site->generateSiteId();

			$site->addLocale( $default_locale );
			$site->addURL( $default_locale, $nonSSL );
			$site->addURL( $default_locale, $SSL );

			foreach(Installer::getSelectedLocales() as $locale) {
				if($locale->toString()==$default_locale->toString()) {
					continue;
				}

				$site->addLocale( $locale );
				$site->addURL( $locale, $nonSSL.$locale.'/' );
				$site->addURL( $locale, $SSL.$locale.'/' );
			}

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

				IO_Dir::copy(
					JET_APP_INSTALLER_DATA_PATH.'site_template/layouts/',
					$site->getLayoutsPath()
				);


				$template_base_path = JET_APP_INSTALLER_DATA_PATH.'site_template/pages/';

				foreach( $site->getLocales() as $locale ) {
					IO_Dir::copy(
						$template_base_path.$locale,
						$site->getPagesDataPath($locale)
					);
				}


				$site->saveDataFile();
				$site->saveUrlMapFile();


				Http_Headers::movedPermanently('?');
			}

		}


		//----------------------------------------------------------------------
		$main_form_fields = [];

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

			$site->setName( 'Example site' );
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

			Http_Headers::movedPermanently('?create');

		}


		//----------------------------------------------------------------------

		$this->view->setVar('site', $site );
		$this->view->setVar('main_form', $main_form);

		$this->render('default');
	}

}
