<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace JetApplicationModule\JetExample\System\Administrators\Users;

use Jet\Mvc_Controller;
use Jet\Mvc_Controller_Router;
use JetApplication\Mvc_Page;
use JetApplication\Auth_Administrator_User as User;

/**
 *
 */
class Controller_Main_Router extends Mvc_Controller_Router
{

	/**
	 * @param Mvc_Controller $controller
	 */
	public function __construct( Mvc_Controller $controller )
	{

		parent::__construct( $controller );

		$validator = function( $parameters ) use ($controller) {

			$user = User::get( $parameters[0] );

			if( !$user ) {
				return false;
			}

			$controller->getContent()->setParameter('user', $user);

			return true;

		};

		$page = Mvc_Page::get( Main::ADMIN_MAIN_PAGE );
		$router = $this;

		$URI_creator = function( $action, $action_uri, $id = 0 ) use ( $router, $page ) {
			if( !$router->getActionAllowed( $action ) ) {
				return false;
			}


			if( !$id ) {
				return $page->getURI([ $action_uri ]);
			}

			return $page->getURI([$action_uri.':'.$id ]);
		};


		$this->addAction( 'add', '/^add$/', Main::ACTION_ADD_USER )->setCreateURICallback(
			function() use ( $URI_creator ) {
				return $URI_creator( 'add', 'add' );
			}
		);

		$this->addAction( 'edit', '/^edit:([0-9]+)$/', Main::ACTION_UPDATE_USER )->setCreateURICallback(
			function( $id ) use ( $URI_creator ) {
				return $URI_creator( 'edit', 'edit', $id );
			}
		)->setParametersValidatorCallback( $validator );

		$this->addAction( 'view', '/^view:([0-9]+)$/', Main::ACTION_GET_USER )->setCreateURICallback(
			function( $id ) use ( $URI_creator ) {
				return $URI_creator( 'view', 'view', $id );
			}
		)->setParametersValidatorCallback( $validator );

		$this->addAction( 'delete', '/^delete:([0-9]+)$/', Main::ACTION_DELETE_USER )->setCreateURICallback(
			function( $id ) use ( $URI_creator ) {
				return $URI_creator( 'delete', 'delete', $id );
			}
		)->setParametersValidatorCallback( $validator );


		return $router;
	}


	/**
	 * @return bool|string
	 */
	public function getAddURI()
	{
		return $this->getActionURI( 'add' );
	}

	/**
	 * @param int $id
	 *
	 * @return bool|string
	 */
	public function getEditOrViewURI( $id )
	{
		if( !( $uri = $this->getEditURI( $id ) ) ) {
			$uri = $this->getViewURI( $id );
		}

		return $uri;
	}

	/**
	 * @param int $id
	 *
	 * @return bool|string
	 */
	public function getEditURI( $id )
	{
		return $this->getActionURI( 'edit', $id );
	}

	/**
	 * @param int $id
	 *
	 * @return bool|string
	 */
	public function getViewURI( $id )
	{
		return $this->getActionURI( 'view', $id );
	}

	/**
	 * @param int $id
	 *
	 * @return bool|string
	 */
	public function getDeleteURI( $id )
	{
		return $this->getActionURI( 'delete', $id );
	}

}