<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */

namespace JetApplication\Installer;

use Jet\Db;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Db_Config;
use Jet\Db_Factory;
use Jet\Db_Backend_Config;
use Jet\Db_Backend_PDO_Config;
use Jet\DataModel_Config;
use Jet\DataModel_Backend_SQLite_Config;
use Jet\SysConf_Path;

/**
 *
 */
class Installer_DbDriverConfig_sqlite extends Installer_DbDriverConfig
{

	/**
	 * @param Db_Config $db_config
	 * @param DataModel_Config $data_model_config
	 *
	 * @return Db_Backend_Config|Db_Backend_PDO_Config
	 */
	public function initialize( Db_Config $db_config, DataModel_Config $data_model_config ) : Db_Backend_Config|Db_Backend_PDO_Config
	{
		$connection_config = Db_Factory::getBackendConfigInstance();
		$connection_config->setName( 'default' );
		$connection_config->setDriver( Db::DRIVER_SQLITE );
		$connection_config->setUsername( '' );
		$connection_config->setPassword( '' );
		$connection_config->setDSN( SysConf_Path::getData() . 'database.sq3' );

		$db_config->addConnection( 'default', $connection_config );


		$data_model_config->setBackendType( 'SQLite' );
		$data_model_backend_config = $data_model_config->getBackendConfig();

		/**
		 * @var DataModel_Backend_SQLite_Config $data_model_backend_config
		 */
		$data_model_backend_config->setConnection( 'default' );

		return $connection_config;
	}


	/**
	 * @return Form
	 */
	public function getForm() : Form
	{
		if( !$this->_form ) {

			$dp = '';

			if( $this->connection_config->getDsn() ) {
				$dp = explode( ':', $this->connection_config->getDsn() );
				$dp = $dp[1];
			}

			$data_path = new Form_Field_Input( 'data_path', 'Data path:', $dp );
			$data_path->setIsRequired( true );
			$data_path->setErrorMessages(
				[
					Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter username',
				]
			);

			$form = new Form(
				'edit_connection', [
					$data_path
				]
			);

			$form->setAutocomplete( false );

			$this->_form = $form;
		}

		return $this->_form;
	}

	/**
	 * @return bool
	 */
	public function catchForm() : bool
	{
		$form = $this->getForm();

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$data_path = $form->getField( 'data_path' );

			$this->connection_config->setUsername( '' );
			$this->connection_config->setPassword( '' );
			$this->connection_config->setDSN( $data_path->getValue() );

			return true;
		}


		return false;
	}


}