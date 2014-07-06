<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category JetApplicationModule
 * @package JetApplicationModule_AdminRoles
 */
namespace JetApplicationModule\JetExample\AdminRoles;
use Jet;

class Main extends Jet\Application_Modules_Module_Abstract {
	protected $ACL_actions = array(
		'get_role' => 'Get role(s) data',
		'add_role' => 'Add new role',
		'update_role' => 'Update role',
		'delete_role' => 'Delete role',
	);

	/**
	 * @var int
	 */
	protected $public_list_items_per_page = 10;

	/**
	 * @var Jet\Mvc_MicroRouter
	 */
	protected $_micro_router;


	/**
	 * @param Jet\Mvc_Router_Abstract $router
	 *
	 * @return Jet\Mvc_MicroRouter
	 */
	public function getMicroRouter( Jet\Mvc_Router_Abstract $router=null ) {
		if($this->_micro_router) {
			return $this->_micro_router;
		}

		if(!$router) {
			$router = Jet\Mvc_Router::getCurrentRouterInstance();
		}

		$router = new Jet\Mvc_MicroRouter( $router, $this );


		$validator = function( &$parameters ) {
			$role_i = Jet\Auth_Factory::getRoleInstance();

			$role = $role_i->load( $role_i->createID($parameters[0]) );
			if(!$role) {
				return false;
			}

			$parameters[0] = $role;
			return true;

		};

		$base_URI = Jet\Mvc::getCurrentURI();

		$router->addAction('add', '/^add$/', 'add_role', true)
			->setCreateURICallback( function() use($base_URI) { return $base_URI.'add/'; } );

		$router->addAction('edit', '/^edit:([\S]+)$/', 'update_role', true)
			->setCreateURICallback( function( Jet\Auth_Role_Abstract $role ) use($base_URI) { return $base_URI.'edit:'.rawurlencode($role->getID()).'/'; } )
			->setParametersValidatorCallback( $validator );

		$router->addAction('view', '/^view:([\S]+)$/', 'get_role', true)
			->setCreateURICallback( function( Jet\Auth_Role_Abstract $role ) use($base_URI) { return $base_URI.'view:'.rawurlencode($role->getID()).'/'; } )
			->setParametersValidatorCallback( $validator );

		$router->addAction('delete', '/^delete:([\S]+)$/', 'delete_role', true)
			->setCreateURICallback( function( Jet\Auth_Role_Abstract $role ) use($base_URI) { return $base_URI.'delete:'.rawurlencode($role->getID()).'/'; } )
			->setParametersValidatorCallback( $validator );

		$this->_micro_router = $router;

		return $router;
	}


	/**
	 * @param Jet\Mvc_Router_Abstract $router
	 * @param Jet\Mvc_Dispatcher_Queue_Item $dispatch_queue_item
	 */
	public function resolveRequest( Jet\Mvc_Router_Abstract $router, Jet\Mvc_Dispatcher_Queue_Item $dispatch_queue_item=null ) {
		$router = $this->getMicroRouter( $router );

		$router->resolve( $dispatch_queue_item );
	}
}