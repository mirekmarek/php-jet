<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Exception;
use Jet\Data_DateTime;
use Jet\IO_File;
use Jet\SysConf_Path;
use Jet\Translator;
use Jet\UI_messages;
use Jet\Tr;


/**
 *
 */
class Installer_Step_Final_Controller extends Installer_Step_Controller
{
	protected string $icon = 'flag-checkered';
	
	/**
	 * @var string
	 */
	protected string $label = 'Installation finish';


	/**
	 *
	 */
	public function main(): void
	{

		$OK = true;

		$install_symptom_file_path = SysConf_Path::getData() . 'installed.txt';


		if(
			Installer_Step_CreateBases_Controller::basesCreated()
		) {
			try {
				IO_File::write( $install_symptom_file_path, Data_DateTime::now()->toString() );
			} catch( Exception $e ) {
				UI_messages::danger( Tr::_( 'Something went wrong: %error%', ['error' => $e->getMessage()], Translator::COMMON_DICTIONARY ) );
				$OK = false;
			}
		}

		if( $OK ) {
			session_reset();
			$this->render( 'done' );
		} else {
			$this->render( 'error' );
		}
	}


}
