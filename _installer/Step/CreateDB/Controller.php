<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplication\Installer;

use Exception;
use Jet\DataModel_Helper;

use JetApplication\Auth_Administrator_Role;
use JetApplication\Auth_Administrator_Role_Privilege;
use JetApplication\Auth_Administrator_User;
use JetApplication\Auth_Administrator_User_Roles;

use JetApplication\Auth_Visitor_Role;
use JetApplication\Auth_Visitor_Role_Privilege;
use JetApplication\Auth_Visitor_User;
use JetApplication\Auth_Visitor_User_Roles;

use JetApplication\Auth_RESTClient_Role;
use JetApplication\Auth_RESTClient_Role_Privilege;
use JetApplication\Auth_RESTClient_User;
use JetApplication\Auth_RESTClient_User_Roles;

use JetApplication\Logger_Admin_Event;
use JetApplication\Logger_Web_Event;
use JetApplication\Logger_REST_Event;

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
	public function getIsAvailable(): bool
	{
		return !Installer_Step_CreateBases_Controller::basesCreated();
	}


	/**
	 *
	 */
	public function main(): void
	{
		$this->catchContinue();


		$classes = [
			Auth_Administrator_Role::class,
			Auth_Administrator_Role_Privilege::class,
			Auth_Administrator_User::class,
			Auth_Administrator_User_Roles::class,

			Auth_Visitor_Role::class,
			Auth_Visitor_Role_Privilege::class,
			Auth_Visitor_User::class,
			Auth_Visitor_User_Roles::class,

			Auth_RESTClient_Role::class,
			Auth_RESTClient_Role_Privilege::class,
			Auth_RESTClient_User::class,
			Auth_RESTClient_User_Roles::class,

			Logger_Admin_Event::class,
			Logger_Web_Event::class,
			Logger_REST_Event::class,
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
