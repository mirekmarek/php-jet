<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Db;
use Jet\Db_Config;
use Jet\Db_Backend_PDO_Config;
use Jet\Http_Request;
use Jet\Http_Headers;
use Jet\UI_messages;
use Jet\Tr;

/**
 *
 */
class Installer_Step_ConfigureDb_Controller extends Installer_Step_Controller
{

	/**
	 * @var string
	 */
	protected $label = 'Database configuration';

	/**
	 * @var Db_Config
	 */
	protected $main_config;

	/**
	 * @return bool
	 */
	public function getIsAvailable()
	{
		return !Installer_Step_CreateSite_Controller::sitesCreated();
	}

	/**
	 *
	 */
	public function main()
	{

		$this->main_config = new Db_Config();

		$connection_name = 'default';

		/**
		 * @var Db_Backend_PDO_Config $connection_config
		 */
		$connection_config = $this->main_config->getConnection( $connection_name );
		if( !$connection_config ) {
			return;
		}


		$GET = Http_Request::GET();

		if( $GET->exists( 'test_connection' ) ) {
			$this->test( $connection_config );
		} else {
			$this->configure( $connection_config );
		}
	}


	/**
	 * @param $connection_config
	 */
	protected function configure( Db_Backend_PDO_Config $connection_config )
	{
		$driver = $connection_config->getDriver();

		require JET_APP_INSTALLER_PATH.'Classes/DbDriverConfig.php';
		require JET_APP_INSTALLER_PATH.'Classes/DbDriverConfig/'.$driver.'.php';

		$class_name = __NAMESPACE__.'\\Installer_DbDriverConfig_'.$driver;

		/**
		 * @var Installer_DbDriverConfig $driver_config
		 */
		$driver_config = new $class_name( $connection_config );

		$form = $driver_config->getForm();

		if($driver_config->catchForm()) {

			$ok = true;

			try {
				$this->main_config->writeConfigFile();
			} catch( \Exception $e ) {
				$ok = false;
				UI_messages::danger( Tr::_('Something went wrong: %error%', ['error'=>$e->getMessage()]) );
			}

			if($ok) {
				Http_Headers::movedTemporary( '?test_connection' );
			}
		}

		$this->view->setVar( 'form', $form );

		$this->render( 'edit-connection' );
	}

	/**
	 * @param $connection_config
	 */
	protected function test( Db_Backend_PDO_Config $connection_config )
	{


		$OK = true;
		$error_message = '';
		try {
			Db::get( $connection_config->getName() );
		} catch( \Exception $e ) {
			$error_message = $e->getMessage();
			$OK = false;
		}

		if( $OK ) {
			$this->catchContinue();
		}
		$form = $connection_config->getCommonForm();

		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'connection_name', $connection_config->getName() );
		$this->view->setVar( 'config', $connection_config );
		$this->view->setVar( 'OK', $OK );
		$this->view->setVar( 'error_message', $error_message );


		$this->render( 'test-connection' );

	}


}
