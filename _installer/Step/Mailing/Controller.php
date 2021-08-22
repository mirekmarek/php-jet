<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Exception;
use Jet\Mailing_Config_Sender;
use Jet\Mvc_Base;
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
	public function main(): void
	{
		$config = new Mailing_Config();

		$known_senders = [];
		$specification = '';

		foreach( Mvc_Base::getAllBases() as $base ) {
			$base_id = $base->getId();

			foreach( $base->getLocales() as $locale ) {

				if( !$config->getSender( $locale, $base_id, $specification ) ) {
					$sender = new Mailing_Config_Sender();

					$config->addSender( $sender, $locale, $base_id, $specification );
				}

				$known_senders[] = $config->getSenderKey( $locale, $base_id, $specification );
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
				$config->saveConfigFile();
			} catch( Exception $e ) {
				UI_messages::danger( Tr::_( 'Something went wrong: %error%', ['error' => $e->getMessage()], Tr::COMMON_NAMESPACE ) );
				Http_Headers::reload();
			}

			Installer::goToNext();
		}


		$this->view->setVar( 'config', $config );
		$this->view->setVar( 'form', $form );

		$this->render( 'default' );


	}

}
