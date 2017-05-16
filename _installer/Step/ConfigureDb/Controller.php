<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetExampleApp;

use Jet\Db;
use Jet\Db_Config;
use Jet\Form;
use Jet\Form_Field_Input;
use Jet\Http_Request;
use Jet\Http_Headers;
use Jet\Mvc_Site;

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
		return count( Mvc_Site::getList() )==0;
	}

	/**
	 *
	 */
	public function main()
	{

		$this->main_config = new Db_Config( true );

		$connection_name = 'default';
		$connection_type = 'mysql';

		$this->view->setVar( 'connection_type', $connection_type );

		$GET = Http_Request::GET();

		if( $GET->exists( 'test_connection' ) ) {
			$this->{'_testConnection_'.$connection_type}( $connection_name );
		} else {
			$this->{'_editConnection_'.$connection_type}( $connection_name );
		}

	}


	/**
	 * @param string $edit_connection_name
	 */
	protected function _editConnection_mysql( $edit_connection_name )
	{
		$connection_config = $this->main_config->getConnection( $edit_connection_name );
		if( !$connection_config ) {
			return;
		}

		$DSN_data = [
			'host' => 'localhost', 'port' => 3306, 'dbname' => '', 'unix_socket' => '',
		];

		$DSN = $connection_config->getDsn();
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


		$username = new Form_Field_Input( 'username', 'Username:', $connection_config->getUsername() );
		$username->setIsRequired( true );
		$username->setErrorMessages(
			[
				Form_Field_Input::ERROR_CODE_EMPTY => 'Please enter username',
			]
		);

		$password = new Form_Field_Input( 'password', 'Password:', $connection_config->getPassword() );
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

		if( $form->catchInput()&&$form->validate() ) {

			if( $unix_socket->getValue() ) {
				$DSN = 'unix_socket='.$unix_socket->getValue();
			} else {
				$DSN = 'host='.$host->getValue().';port='.$port->getValue();
			}

			$DSN .= ';dbname='.$dbname->getValue();
			$DSN .= ';charset=utf8';

			$connection_config->setUsername( $username->getValue() );
			$connection_config->setPassword( $password->getValue() );
			$connection_config->setDSN( $DSN );

			$this->main_config->save();

			Http_Headers::movedTemporary( '?test_connection' );
		}

		$this->view->setVar( 'form', $form );

		$this->render( 'edit-connection' );
	}

	/**
	 * @param string $test_connection_name
	 */
	protected function _testConnection_mysql( $test_connection_name )
	{
		$connection_config = $this->main_config->getConnection( $test_connection_name );
		if( !$connection_config ) {
			return;
		}

		$form = $connection_config->getCommonForm();

		$OK = true;
		$error_message = '';
		try {
			Db::get( $test_connection_name );
		} catch( \Exception $e ) {
			$error_message = $e->getMessage();
			$OK = false;
		}

		if( $OK ) {
			if( Http_Request::POST()->exists( 'go' ) ) {
				Installer::goToNext();
			}
		}

		$this->view->setVar( 'form', $form );
		$this->view->setVar( 'connection_name', $test_connection_name );
		$this->view->setVar( 'config', $connection_config );
		$this->view->setVar( 'OK', $OK );
		$this->view->setVar( 'error_message', $error_message );


		$this->render( 'test-connection' );

	}


}
