<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Jet\Data_Array;
use Jet\Exception;
use Jet\Form;
use Jet\Form_Field;
use Jet\Form_Field_Input;
use Jet\Form_Field_Password;
use Jet\IO_File;
use Jet\SysConf_Path;
use Jet\Tr;
use Jet\Translator;
use Jet\UI_messages;

/**
 *
 */
class Installer_Step_ConfigureStudio_Controller extends Installer_Step_Controller
{
	protected string $icon = 'laptop-code';
	
	/**
	 * @var string
	 */
	protected string $label = 'Configure Jet Studio';

	public function main(): void
	{
		$this->catchContinue();

		$username = new Form_Field_Input( 'username', 'Username:' );
		$username->setIsRequired( true );
		$username->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter username'
		] );



		$password = new Form_Field_Password( 'password', 'Password:' );
		$password->setErrorMessages( [
			Form_Field::ERROR_CODE_EMPTY => 'Please enter password',
		] );
		$password_check = $password->generateCheckField(
			field_name: 'password_check',
			field_label: 'Confirm password:',
			error_message_empty: 'Please confirm password',
			error_message_not_match: 'Password confirmation do not match'
		);

		$form = new Form( 'studio_config_form', [
			$username,
			$password,
			$password_check
		] );

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$ok = true;

			try {
				$data = new Data_Array( [
					'username' => $username->getValue(),
					'password' => password_hash( $password->getValue(), PASSWORD_DEFAULT )
				] );


				IO_File::write( SysConf_Path::getData() . '_jet_studio_access.php', '<?php return ' . $data->export() );

			} catch( Exception $e ) {

				UI_messages::danger( Tr::_( 'Something went wrong: %error%', ['error' => $e->getMessage()], Translator::COMMON_DICTIONARY ) );
				$ok = false;
			}

			if( $ok ) {
				Installer::goToNext();
			}
		}

		$this->view->setVar( 'form', $form );
		$this->render( 'default' );

	}
}