<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;


use Jet\Form;
use Jet\Form_Field_Email;

/**
 *
 */
class Installer_Step_Mailing_Controller extends Installer_Step_Controller
{

	/**
	 * @var string
	 */
	protected $label = 'Mailing configuration';

	/**
	 *
	 */
	public function main()
	{

		$config = new Mailing_Config( true );

		$locales = [];
		foreach( Installer::getSelectedLocales() as $locale ) {
			$locales[] = (string)$locale;

			if( !$config->getSender( $locale ) ) {
				$sender_config = new Mailing_Config_Sender( [], $config );

				$config->addSender( $locale, $sender_config );
			}
		}

		foreach( array_keys( $config->getSenders() ) as $e_l ) {
			if( !in_array( $e_l, $locales ) ) {
				$config->deleteSender( $e_l );
			}
		}


		$form = new Form( 'config', [] );

		foreach( $config->getSenders() as $locale => $sender ) {
			$sender_form = $sender->getCommonForm();

			$email = $sender_form->field( 'email' );
			$email->setIsRequired( true );
			$email->setErrorMessages(
				[
					Form_Field_Email::ERROR_CODE_EMPTY          => 'Please enter valid email address',
					Form_Field_Email::ERROR_CODE_INVALID_FORMAT => 'Please enter valid email address',
				]
			);

			$email->setName( '/'.$locale.'/email' );
			$email->setCatcher(
				function( $value ) use ( $sender ) {
					$sender->setEmail( $value );
				}
			);

			$form->addField( $email );


			$name = $sender_form->field( 'name' );
			$name->setName( '/'.$locale.'/name' );
			$name->setCatcher(
				function( $value ) use ( $sender ) {
					$sender->setName( $value );
				}
			);

			$form->addField( $name );
		}


		if( $form->catchInput()&&$form->validate() ) {
			$form->catchData();
			$config->save();

			Installer::goToNext();
		}


		$this->view->setVar( 'config', $config );
		$this->view->setVar( 'form', $form );

		$this->render( 'default' );


	}

}
