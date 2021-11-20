<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Exception;
use Jet\Mailing;
use Jet\Mailing_Config_Sender;
use Jet\UI_messages;
use Jet\Http_Headers;
use Jet\Tr;


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
		$config = Mailing::getConfig();

		if(!$config->getSender(Mailing::DEFAULT_SENDER_ID)) {
			$sender = new Mailing_Config_Sender();
			$config->addSender( Mailing::DEFAULT_SENDER_ID, $sender );
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
				UI_messages::danger( Tr::_( 'Something went wrong: %error%', ['error' => $e->getMessage()], Tr::COMMON_DICTIONARY ) );
				Http_Headers::reload();
			}

			Installer::goToNext();
		}

		$this->view->setVar( 'config', $config );
		$this->view->setVar( 'form', $form );

		$this->render( 'default' );
	}
}
