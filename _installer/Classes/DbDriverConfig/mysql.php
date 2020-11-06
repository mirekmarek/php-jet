<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\Db;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Form_Field_Password;
use Jet\Db_Config;
use Jet\Db_Factory;
use Jet\Db_Backend_Config;
use Jet\Db_Backend_PDO_Config;
use Jet\DataModel_Config;
use Jet\DataModel_Backend_MySQL_Config;

/**
 *
 */
class Installer_DbDriverConfig_mysql extends Installer_DbDriverConfig
{
	/**
	 * @param Db_Config $db_config
	 * @param DataModel_Config $data_model_config
	 *
	 * @return Db_Backend_Config|Db_Backend_PDO_Config
	 */
	public function initialize( Db_Config $db_config, DataModel_Config $data_model_config )
	{
		$connection_config = Db_Factory::getBackendConfigInstance();
		$connection_config->setName( 'default' );
		$connection_config->setDriver( Db::DRIVER_MYSQL );
		$connection_config->setUsername( '' );
		$connection_config->setPassword( '' );
		$connection_config->setDSN( 'host=localhost;port=3306;dbname=;charset=utf8' );

		$db_config->addConnection( 'default', $connection_config );



		$data_model_config->setBackendType( 'MySQL' );
		$data_model_backend_config = $data_model_config->getBackendConfig();

		/**
		 * @var DataModel_Backend_MySQL_Config $data_model_backend_config
		 */
		$data_model_backend_config->setConnectionRead( 'default' );
		$data_model_backend_config->setConnectionWrite( 'default' );

		return $connection_config;
	}

	/**
	 * @return Form
	 */
	public function getForm()
	{
		if(!$this->_form) {
			$DSN_data = [
				'host'        => 'localhost',
				'port'        => 3306,
				'dbname'      => '',
				'unix_socket' => '',
			];

			$DSN = $this->connection_config->getDsn();
			$DSN = explode( ':', $DSN );

			if( count( $DSN )==2 ) {
				$DSN = $DSN[1];
				$DSN = explode( ';', $DSN );

				foreach( $DSN as $DSN_line ) {
					$DSN_line = explode( '=', $DSN_line );
					if( count( $DSN_line )!=2 ) {
						continue;
					}

					list( $key, $val ) = $DSN_line;

					if( array_key_exists( $key, $DSN_data ) ) {
						$DSN_data[$key] = $val;
					}
				}
			}


			$username = new Form_Field_Input( 'username', 'Username:', $this->connection_config->getUsername() );
			$username->setIsRequired( true );
			$username->setErrorMessages(
				[
					Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter username',
				]
			);

			$password = new Form_Field_Password( 'password', 'Password:', $this->connection_config->getPassword() );
			$password->setIsRequired( true );
			$password->setErrorMessages(
				[
					Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter the password',
				]
			);

			$dbname = new Form_Field_Input( 'dbname', 'Database:', $DSN_data['dbname'] );
			$dbname->setIsRequired( true );
			$dbname->setErrorMessages(
				[
					Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter the name of the database',
				]
			);


			$host = new Form_Field_Input( 'host', 'Host:', $DSN_data['host'] );
			$port = new Form_Field_Input( 'port', 'Port:', $DSN_data['port'] );
			$unix_socket = new Form_Field_Input( 'unix_socket', 'Unix socket path:', $DSN_data['unix_socket'] );


			$form = new Form(
				'edit_connection', [
					$username, $password, $dbname, $host, $port, $unix_socket,
				]
			);

			$form->setAutocomplete(false);

			$this->_form = $form;
		}

		return $this->_form;
	}

	/**
	 * @return bool
	 */
	public function catchForm()
	{
		$form = $this->getForm();

		if(
			$form->catchInput() &&
			$form->validate()
		) {
			$unix_socket = $form->getField('unix_socket');
			$host = $form->getField('host');
			$username = $form->getField('username');
			$password = $form->getField('password');
			$dbname = $form->getField('dbname');
			$port = $form->getField('port');

			if( $unix_socket->getValue() ) {
				$DSN = 'unix_socket='.$unix_socket->getValue();
			} else {
				$DSN = 'host='.$host->getValue().';port='.$port->getValue();
			}

			$DSN .= ';dbname='.$dbname->getValue();
			$DSN .= ';charset=utf8';

			$this->connection_config->setUsername( $username->getValue() );
			$this->connection_config->setPassword( $password->getValue() );
			$this->connection_config->setDSN( $DSN );

			return true;
		}


		return false;
	}

}