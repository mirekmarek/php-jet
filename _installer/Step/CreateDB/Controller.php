<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetExampleApp;

use Jet\Http_Request;
use Jet\DataModel_Helper;
use Jet\Exception;
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
		return count( Mvc_Site::getList() )==0;
	}


	/**
	 *
	 */
	public function main()
	{
		if( Http_Request::POST()->exists( 'go' ) ) {
			Installer::goToNext();
		}


		$classes = [
			__NAMESPACE__.'\Auth_Administrator_Role', __NAMESPACE__.'\Auth_Administrator_Role_Privilege',
			__NAMESPACE__.'\Auth_Administrator_User', __NAMESPACE__.'\Auth_Administrator_User_Roles',

			__NAMESPACE__.'\Auth_Visitor_Role', __NAMESPACE__.'\Auth_Visitor_Role_Privilege',
			__NAMESPACE__.'\Auth_Visitor_User', __NAMESPACE__.'\Auth_Visitor_User_Roles',

			__NAMESPACE__.'\Application_Logger_Event_Administration', __NAMESPACE__.'\Application_Logger_Event_Site',
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
