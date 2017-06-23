<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplication;

use Jet\DataModel_Helper;
use Jet\Mvc_Site;

/**
 *
 */
class Installer_Step_CreateDB_Controller extends Installer_Step_Controller
{

	/**
	 * @var string
	 */
	protected $label = 'Create database';

	/**
	 * @return bool
	 */
	public function getIsAvailable()
	{
		return count( Mvc_Site::loadSites() )==0;
	}


	/**
	 *
	 */
	public function main()
	{
		$this->catchContinue();


		$classes = [
			__NAMESPACE__.'\Auth_Administrator_Role',
			__NAMESPACE__.'\Auth_Administrator_Role_Privilege',
			__NAMESPACE__.'\Auth_Administrator_User',
			__NAMESPACE__.'\Auth_Administrator_User_Roles',

			__NAMESPACE__.'\Auth_Visitor_Role',
			__NAMESPACE__.'\Auth_Visitor_Role_Privilege',
			__NAMESPACE__.'\Auth_Visitor_User',
			__NAMESPACE__.'\Auth_Visitor_User_Roles',

			__NAMESPACE__.'\Auth_RESTClient_Role',
			__NAMESPACE__.'\Auth_RESTClient_Role_Privilege',
			__NAMESPACE__.'\Auth_RESTClient_User',
			__NAMESPACE__.'\Auth_RESTClient_User_Roles',

			__NAMESPACE__.'\Application_Log_Logger_Admin_Event',
			__NAMESPACE__.'\Application_Log_Logger_Web_Event',
			__NAMESPACE__.'\Application_Log_Logger_REST_Event',
		];

		$result = [];
		$OK = true;

		foreach( $classes as $class ) {
			$result[$class] = true;
			try {
				DataModel_Helper::create( $class );
			} catch( \Exception $e ) {
				$result[$class] = $e->getMessage();
				$OK = false;
			}

		}

		$this->view->setVar( 'result', $result );
		$this->view->setVar( 'OK', $OK );

		$this->render( 'default' );
	}

}
