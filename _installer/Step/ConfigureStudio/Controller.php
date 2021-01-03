<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication\Installer;
use Jet\Data_Array;
use Jet\Exception;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_RegistrationPassword;
use Jet\IO_File;
use Jet\SysConf_Path;
use Jet\Tr;
use Jet\UI_messages;

/**
 *
 */
class Installer_Step_ConfigureStudio_Controller extends Installer_Step_Controller
{

	/**
	 * @var string
	 */
	protected string $label = 'Configure Jet Studio';

	public function main() : void
	{
		$this->catchContinue();

		$username = new Form_Field_Input('username', 'Username:');
		$username->setIsRequired(true);
		$username->setErrorMessages([
			Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter username'
		]);

		$password = new Form_Field_RegistrationPassword('password', 'Password:');
		$password->setPasswordConfirmationLabel( 'Confirm password:' );
		$password->setErrorMessages([
			Form_Field_RegistrationPassword::ERROR_CODE_EMPTY           => 'Please enter password',
			Form_Field_RegistrationPassword::ERROR_CODE_CHECK_EMPTY     => 'Please confirm password',
			Form_Field_RegistrationPassword::ERROR_CODE_CHECK_NOT_MATCH => 'Password confirmation do not match',
			Form_Field_RegistrationPassword::ERROR_CODE_WEAK_PASSWORD   => 'Password is not strong enough',
		]);

		$form = new Form( 'studio_config_form', [
			$username,
			$password
		]);

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$ok = true;

			try {
				$data = new Data_Array([
					'username' => $username->getValue(),
					'password' => password_hash( $password->getValue(), PASSWORD_DEFAULT )
				]);


				IO_File::write( SysConf_Path::getData().'_jet_studio_access.php', '<?php return '.$data->export() );

			} catch (Exception $e) {

				UI_messages::danger( Tr::_('Something went wrong: %error%', ['error'=>$e->getMessage()], Tr::COMMON_NAMESPACE) );
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