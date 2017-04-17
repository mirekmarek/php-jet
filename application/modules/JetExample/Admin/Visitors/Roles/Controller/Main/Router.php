<?php
/**
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetApplicationModule\JetExample\Admin\Visitors\Roles;

use Jet\Mvc_Page;
use Jet\Mvc_Controller_Router;
use JetExampleApp\Auth_Visitor_Role as Role;

class Controller_Main_Router extends Mvc_Controller_Router {


	/**
	 *
	 * @param Main $module_instance
	 */
	public function __construct( Main $module_instance )
	{

		parent::__construct( $module_instance );

		$validator = function( &$parameters ) {

			$role = Role::get($parameters[0]);

			if(!$role) {
				return false;
			}

			$parameters['role'] = $role;
			return true;

		};

		$base_URI = Mvc_Page::get(Main::PAGE_ROLES)->getURI();
		$router = $this;

		$URI_creator = function( $action, $action_uri, $id=0 ) use ($router, $base_URI) {
			if(!$router->getActionAllowed($action)) {
				return false;
			}


			if(!$id) {
				return $action_uri.'/';
			}

			return $base_URI.$action_uri.':'.((int)$id).'/';
		};


		$this->addAction('add', '/^add$/', Main::ACTION_ADD_ROLE )
			->setCreateURICallback( function() use($URI_creator) { return $URI_creator('add', 'add'); } );

		$this->addAction('edit', '/^edit:([0-9]+)$/', Main::ACTION_UPDATE_ROLE )
			->setCreateURICallback( function($id) use($URI_creator) { return $URI_creator('edit', 'edit', $id); } )
			->setParametersValidatorCallback( $validator );

		$this->addAction('view', '/^view:([0-9]+)$/', Main::ACTION_GET_ROLE )
			->setCreateURICallback( function($id) use($URI_creator) { return $URI_creator('view', 'view', $id); } )
			->setParametersValidatorCallback( $validator );

		$this->addAction('delete', '/^delete:([0-9]+)$/', Main::ACTION_DELETE_ROLE )
			->setCreateURICallback( function($id) use($URI_creator) { return $URI_creator('delete', 'delete', $id); } )
			->setParametersValidatorCallback( $validator );


		return $router;
	}


	/**
	 * @return bool|string
	 */
	public function getAddURI() {
		return $this->getActionURI('add');
	}

	/**
	 * @param int $id
	 * @return bool|string
	 */
	public function getEditURI( $id ) {
		return $this->getActionURI('edit', $id );
	}

	/**
	 * @param int $id
	 * @return bool|string
	 */
	public function getEditOrViewURI( $id ) {
		if( !($uri=$this->getEditURI( $id )) ) {
			$uri = $this->getViewURI( $id );
		}

		return $uri;
	}

	/**
	 * @param int $id
	 * @return bool|string
	 */
	public function getViewURI( $id ) {
		return $this->getActionURI('view', $id );
	}

	/**
	 * @param int $id
	 * @return bool|string
	 */
	public function getDeleteURI( $id ) {
		return $this->getActionURI('delete', $id );
	}

}