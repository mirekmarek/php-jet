<?php
/**
 *
 * @copyright Copyright (c) 2011-2020 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication\Installer;

use Exception;
use Jet\DataModel_Helper;

/**
 *
 */
class Installer_Step_CreateDB_Controller extends Installer_Step_Controller
{

	/**
	 * @var string
	 */
	protected string $label = 'Create database';

	/**
	 * @return bool
	 */
	public function getIsAvailable() : bool
	{
		return !Installer_Step_CreateSite_Controller::sitesCreated();
	}


	/**
	 *
	 */
	public function main() : void
	{
		$this->catchContinue();

		$namespace = Installer::getApplicationNamespace();

		$classes = [
			$namespace.'\Auth_Administrator_Role',
			$namespace.'\Auth_Administrator_Role_Privilege',
			$namespace.'\Auth_Administrator_User',
			$namespace.'\Auth_Administrator_User_Roles',

			$namespace.'\Auth_Visitor_Role',
			$namespace.'\Auth_Visitor_Role_Privilege',
			$namespace.'\Auth_Visitor_User',
			$namespace.'\Auth_Visitor_User_Roles',

			$namespace.'\Auth_RESTClient_Role',
			$namespace.'\Auth_RESTClient_Role_Privilege',
			$namespace.'\Auth_RESTClient_User',
			$namespace.'\Auth_RESTClient_User_Roles',

			$namespace.'\Application_Logger_Admin_Event',
			$namespace.'\Application_Logger_Web_Event',
			$namespace.'\Application_Logger_REST_Event',
		];

		$result = [];
		$OK = true;

		foreach( $classes as $class ) {
			$result[$class] = true;
			try {
				DataModel_Helper::create( $class );
			} catch( Exception $e ) {
				$result[$class] = $e->getMessage();
				$OK = false;
			}

		}

		$this->view->setVar( 'result', $result );
		$this->view->setVar( 'OK', $OK );

		$this->render( 'default' );
	}

}
