<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication\Installer;

use Exception;
use Jet\Mailing_Config_Sender;
use Jet\Mvc_Site;
use Jet\UI_messages;
use Jet\Http_Headers;
use Jet\Tr;
use Jet\Mailing_Config;


/**
 *
 */
class Installer_Step_Mailing_Controller extends Installer_Step_Controller
{

	/**
	 * @var string
	 */
	protected string $label = 'Mailing configuration';

	/**
	 *
	 */
	public function main() : void
	{
		$config = new Mailing_Config();

		$known_senders = [];
		$specification = '';

		foreach( Mvc_Site::getAllSites() as $site ) {
			$site_id = $site->getId();

			foreach( $site->getLocales() as $locale ) {

				if(!$config->getSender( $locale,$site_id, $specification )) {
					$sender = new Mailing_Config_Sender();

					$config->addSender( $sender, $locale, $site_id, $specification );
				}

				$known_senders[] = $config->getSenderKey( $locale, $site_id, $specification );
			}
		}


		foreach( array_keys( $config->getSenders() ) as $key ) {
			if( !in_array( $key, $known_senders ) ) {
				$config->deleteSender( $key );
			}
		}

		$form = $config->getCommonForm();

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$form->catchData();


			try {
				$config->writeConfigFile();
			} catch( Exception $e ) {
				UI_messages::danger( Tr::_('Something went wrong: %error%', ['error'=>$e->getMessage()], Tr::COMMON_NAMESPACE) );
				Http_Headers::reload();
			}

			Installer::goToNext();
		}


		$this->view->setVar( 'config', $config );
		$this->view->setVar( 'form', $form );

		$this->render( 'default' );


	}

}
